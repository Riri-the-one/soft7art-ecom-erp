<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'returned' fait partie de l'énumération en base et est déjà géré par
            // OrderObserver (remise du stock) et par la page de suivi publique :
            // il manquait seulement ici, ce qui rendait le statut inatteignable.
            'status' => ['required', 'in:pending,confirmed,shipped,delivered,returned,canceled'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Veuillez choisir un statut.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }
}
