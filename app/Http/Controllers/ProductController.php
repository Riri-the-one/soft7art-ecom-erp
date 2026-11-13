<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->query('alert') === 'true') {
            $query->where('stock_quantity', '<', 10);
        }

        if ($request->query('stock') === 'critical') {
            $query->where('stock_quantity', '<', 5);
        }

        $products = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('products.partials.table', compact('products'))->render();
        }

        return view('products.index', compact('products'));
    }

    public function alerts()
    {
        $products = Product::where('stock_quantity', '<', 10)->get();

        return view('products.alerts', compact('products'));
    }

    public function create()
    {
        $this->authorize('manage', Product::class);

        return view('products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('manage', Product::class);

        // 1. Validation stricte des données selon les règles métier
        $validated = $request->validated();

        // 2. Création du produit dans la base de données
        Product::create($validated);

        // 3. Redirection vers le catalogue avec un message de succès
        return redirect()->route('products.index')->with('success', 'Produit ajouté au catalogue avec succès !');
    }

    public function edit(Product $product)
    {
        $this->authorize('manage', Product::class);

        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('manage', Product::class);

        $validated = $request->validated();

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès !');
    }

    /**
     * Étiquette imprimable : code-barres du produit + emplacement de rangement,
     * pour une préparation rapide au scan (téléphone ou douchette).
     */
    public function destroy(Product $product)
    {
        $this->authorize('manage', Product::class);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprim?.');
    }

    public function barcode(Product $product)
    {
        $this->authorize('manage', Product::class);

        return view('products.barcode', compact('product'));
    }
}
