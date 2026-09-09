<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Mail\OrderConfirmedMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        // 'customer.orders' est chargé en plus pour permettre à l'accessor
        // Customer::delivery_rate (badge "Risque élevé" affiché dans la table)
        // de réutiliser cette relation déjà en mémoire, sans requête N+1 par ligne.
        $query = Order::with(['customer.orders', 'user'])->latest();

        // Filtre de recherche par ID commande ou nom client
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
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

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $product = Product::find($validated['product_id']);
        $subtotal = $product->selling_price * $validated['quantity'];
        $totalAmount = $subtotal + $validated['delivery_fee'];

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

        if ($oldStatus !== $newStatus) {
            $order->status = $newStatus;
            $order->save();

            // Envoyer un e-mail de confirmation si le statut est 'confirmed'
            if ($newStatus === 'confirmed') {
                Mail::to($order->customer->email)->send(new OrderConfirmedMail($order));
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Statut mis à jour']);
        }

        return back()->with('success', "Statut de la commande mis à jour de {$oldStatus} à {$newStatus}.");
    }

    public function invoice(Order $order)
    {
        $order->load(['products', 'customer']);
        
        $pdf = Pdf::setOption(['isRemoteEnabled' => true])->loadView('orders.invoice', compact('order'));
        return $pdf->download('facture_commande_' . $order->id . '.pdf');
    }

    public function exportCsv()
    {
        $orders = Order::with('customer')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export_commandes.csv"',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID Commande', 'Nom Client', 'Montant Total', 'Statut', 'Date']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->customer->name ?? '',
                    number_format($order->total_amount, 2, '.', ''),
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}