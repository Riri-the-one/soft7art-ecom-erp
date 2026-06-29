<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::query();

        if (request('alert') == 'true') {
            $query->where('stock_quantity', '<', 10);
        }

        $products = $query->paginate(10)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function alerts()
    {
        $products = Product::where('stock_quantity', '<', 10)->get();
        return view('products.alerts', compact('products'));
    }

    // ... garde la méthode index() existante ...

    public function create()
{
    return view('products.create');
}

public function store(StoreProductRequest $request)
{
    // 1. Validation stricte des données selon les règles métier
    $validated = $request->validated();

    // 2. Création du produit dans la base de données
    \App\Models\Product::create($validated);

    // 3. Redirection vers le catalogue avec un message de succès
    return redirect()->route('products.index')->with('success', 'Produit ajouté au catalogue avec succès !');
}
}