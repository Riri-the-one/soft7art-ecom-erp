<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmedMail;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'user'])->latest();

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

        $orders = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('orders.partials.table', compact('orders'))->render();
        }

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['products', 'customer', 'user', 'activityLogs.user']);

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = auth()->user();
        if (!$user || (! $user->hasRole('super_admin') && ! $user->hasRole('agent'))) {
            abort(403, 'Accès non autorisé : rôle insuffisant.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,shipped,delivered,canceled'],
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        if ($oldStatus !== $newStatus) {
            $order->status = $newStatus;
            $order->save();

            $order->activityLogs()->create([
                'user_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);

            // Envoyer un e-mail de confirmation si le statut est 'confirmed'
            if ($newStatus === 'confirmed') {
                Mail::to($order->customer->email)->send(new OrderConfirmedMail($order));
            }
        }

        return back()->with('success', "Statut de la commande mis à jour de {$oldStatus} à {$newStatus}.");
    }

    public function invoice(Order $order)
    {
        $order->load(['products', 'customer']);
        
        $pdf = Pdf::loadView('orders.invoice', compact('order'));
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