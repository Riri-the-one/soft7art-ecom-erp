<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Mail\OrderConfirmedMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderStatusService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        // 'customer.orders' est chargé en plus pour permettre à l'accessor
        // Customer::delivery_rate (badge "Risque élevé" affiché dans la table)
        // de réutiliser cette relation déjà en mémoire, sans requête N+1 par ligne.
        $query = Order::with(['customer.orders', 'user'])->latest();

        // Filtre de recherche par ID commande ou nom client.
        // Le OR est isolé dans une sous-condition : sans ce groupement, SQL
        // évalue "id LIKE ? OR (client correspond ET statut = ?)", ce qui
        // faisait remonter des commandes hors du statut/de la période demandés
        // dès qu'on combinait la recherche avec un autre filtre.
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%'.$search.'%')
                    ->orWhereHas('customer', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('orders.partials.table', compact('orders'))->render();
        }

        return view('orders.index', compact('orders'));
    }

    public function kanban()
    {
        $this->authorize('viewAny', Order::class);
        $orders = Order::with('customer')->latest()->get();

        return view('orders.kanban', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['products', 'customer', 'user', 'activityLogs.user']);

        return view('orders.show', compact('order'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();

        return view('orders.create', compact('customers', 'products'));
    }

    /**
     * Vérification AJAX (appelée depuis le formulaire de création) : le même
     * client a-t-il déjà passé une commande pour ce même produit dans
     * l'heure qui précède ? Sert uniquement d'avertissement pour l'agent,
     * qui reste libre de confirmer la saisie malgré tout.
     */
    public function checkDuplicate(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $existing = Order::where('customer_id', $validated['customer_id'])
            ->where('created_at', '>=', now()->subHour())
            ->whereNotIn('status', ['canceled', 'returned'])
            ->whereHas('products', function ($query) use ($validated) {
                $query->where('products.id', $validated['product_id']);
            })
            ->with('products')
            ->latest()
            ->first();

        if (! $existing) {
            return response()->json(['duplicate' => false]);
        }

        return response()->json([
            'duplicate' => true,
            'order_id' => $existing->id,
            // (int) : diffInMinutes renvoie un flottant avec Carbon 3, ce qui
            // afficherait "il y a 12.7 minutes" dans l'avertissement.
            'minutes_ago' => (int) $existing->created_at->diffInMinutes(now()),
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);
        $totalCents = (int) round($product->selling_price * 100) * (int) $validated['quantity']
            + (int) round($validated['delivery_fee'] * 100);
        if ($totalCents > 9999999999) {
            throw ValidationException::withMessages(['quantity' => 'Montant maximal de commande dépassé.']);
        }
        $totalAmount = $totalCents / 100;

        // La création de la commande et l'attachement du produit en pivot doivent
        // réussir ensemble : sans transaction, un échec sur le attach() laisserait
        // une commande "fantôme" sans aucun produit rattaché.
        $order = DB::transaction(function () use ($validated, $product, $totalAmount) {
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'status' => 'pending',
                'delivery_fee' => $validated['delivery_fee'],
                'total_amount' => $totalAmount,
            ]);

            $order->products()->attach($validated['product_id'], [
                'quantity' => $validated['quantity'],
                'unit_price' => $product->selling_price,
                'unit_cost' => $product->purchase_price,
            ]);

            return $order;
        });

        return redirect()->route('orders.show', $order)->with('success', 'Commande créée avec succès.');
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $validated = $request->validated();

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        $changed = app(OrderStatusService::class)->transition($order, $newStatus);
        if ($changed) {
            // L'e-mail est envoyé hors transaction et sans bloquer : une panne du
            // serveur SMTP ne doit pas annuler un changement de statut déjà validé.
            if ($newStatus === 'confirmed' && $order->customer && $order->customer->email) {
                try {
                    Mail::to($order->customer->email)->send(new OrderConfirmedMail($order));
                } catch (\Throwable $e) {
                    Log::warning("Envoi de l'e-mail de confirmation échoué pour la commande {$order->id} : ".$e->getMessage());
                }
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Statut mis à jour']);
        }

        return back()->with('success', "Statut de la commande mis à jour de {$oldStatus} à {$newStatus}.");
    }

    public function invoice(Order $order)
    {
        // Même contrôle que show() : la facture contient les mêmes informations
        // client, elle ne doit pas être accessible plus largement que la fiche.
        $this->authorize('view', $order);

        $order->load(['products', 'customer']);

        $pdf = Pdf::setOption(['isRemoteEnabled' => false])->loadView('orders.invoice', compact('order'));

        return $pdf->download('facture_commande_'.$order->id.'.pdf');
    }

    /**
     * Bon de livraison / étiquette compacte (format ~10x15 cm), pensée pour être
     * imprimée directement depuis le navigateur sur une imprimante thermique.
     */
    public function label(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['products', 'customer']);

        return view('orders.label', compact('order'));
    }

    /**
     * Page de suivi PUBLIQUE (pas d'authentification) : le token dans l'URL
     * fait office d'autorisation. Un token invalide ou absent renvoie un 404,
     * sans révéler d'information sur la commande.
     */
    public function track(Order $order, string $token)
    {
        if (empty($order->tracking_token) || ! hash_equals($order->tracking_token, $token)) {
            abort(404);
        }

        return view('orders.track', compact('order'));
    }

    private static function csvCell(string $value): string
    {
        return preg_match('/^[\\s]*[=+@-]/u', $value) ? "'".$value : $value;
    }

    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="export_commandes.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 : sans lui, Excel affiche "Ã©" à la place des accents.
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['ID Commande', 'Nom Client', 'Montant Total', 'Statut', 'Date']);

            // lazy() parcourt les commandes par lots au lieu de toutes les charger
            // en mémoire : l'export reste stable quand le volume grandit.
            foreach (Order::with('customer')->lazy() as $order) {
                fputcsv($handle, [
                    $order->id,
                    self::csvCell($order->customer->name ?? ''),
                    number_format($order->total_amount, 2, '.', ''),
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export "logistique" : uniquement les commandes confirmées (prêtes à être
     * remises au transporteur), au format colonnes attendu par les portails
     * des transporteurs marocains (Cathedis, EcoTrack, Ozone Express...).
     */
    public function exportLogisticsCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="export_logistique_'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM pour qu'Excel affiche correctement les accents français.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Référence', 'Destinataire', 'Téléphone', 'Ville', 'Adresse',
                'Désignation', 'Quantité', 'Montant COD (DH)', 'Remarques',
            ]);

            $orders = Order::where('status', 'confirmed')
                ->with(['customer', 'products'])
                ->lazy();

            foreach ($orders as $order) {
                $customer = $order->customer;
                $designation = $order->products->map(function ($product) {
                    return $product->name.' x'.$product->pivot->quantity;
                })->implode(' + ');

                fputcsv($handle, [
                    'CMD-'.$order->id,
                    self::csvCell($customer->name ?? ''),
                    self::csvCell($customer->phone ?? ''),
                    self::csvCell($customer->city ?? ''),
                    self::csvCell($customer->address ?? ''),
                    self::csvCell($designation),
                    $order->products->sum('pivot.quantity'),
                    number_format($order->total_amount, 2, '.', ''),
                    '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
