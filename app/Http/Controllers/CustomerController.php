<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers with their order count and total spent.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Customer::class);

        $query = Customer::withCount('orders')
            ->with('orders')
            ->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $customers = $query->paginate(15);

        if ($request->ajax()) {
            return view('customers.partials.table', compact('customers'))->render();
        }

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $this->authorize('create', Customer::class);

        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $this->authorize('create', Customer::class);

        Customer::create($request->validated());

        return redirect()->route('customers.index')->with('success', 'Client créé avec succès.');
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);

        $customer->load('orders');

        return view('customers.show', compact('customer'));
    }
}
