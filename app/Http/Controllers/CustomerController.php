<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers with their order count and total spent.
     */
    public function index()
    {
        $customers = Customer::withCount('orders')
            ->with('orders')
            ->latest()
            ->paginate(15);

        return view('customers.index', compact('customers'));
    }
}
