<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Le contrôle d'accès est déjà assuré par le middleware de route
     * ('role:agent,super_admin'), on autorise donc ici.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:10000',
                // La colonne products.stock_quantity est un entier NON SIGNÉ : sans
                // ce contrôle, confirmer une commande dont la quantité dépasse le
                // stock fait échouer la décrémentation au niveau SQL (erreur 500).
                function ($attribute, $value, $fail) {
                    $product = Product::find($this->input('product_id'));

                    if ($product && $value > $product->stock_quantity) {
                        $fail("Stock insuffisant : il ne reste que {$product->stock_quantity} unité(s) de « {$product->name} ».");
                    }
                },
            ],
            'delivery_fee' => ['required', 'numeric', 'min:0', 'max:999999.99', 'decimal:0,2'],
        ];
    }

    /**
     * Messages d'erreur en français, affichés directement dans le formulaire.
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Veuillez sélectionner un client.',
            'customer_id.exists' => 'Le client sélectionné est introuvable.',
            'product_id.required' => 'Veuillez sélectionner un produit.',
            'product_id.exists' => 'Le produit sélectionné est introuvable.',
            'quantity.required' => 'Veuillez indiquer une quantité.',
            'quantity.min' => 'La quantité doit être d\'au moins 1.',
            'delivery_fee.required' => 'Veuillez indiquer les frais de livraison.',
            'delivery_fee.min' => 'Les frais de livraison ne peuvent pas être négatifs.',
        ];
    }
}
