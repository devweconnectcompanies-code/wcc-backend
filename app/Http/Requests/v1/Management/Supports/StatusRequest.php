<?php

namespace App\Http\Requests\v1\Management\Supports;

use App\DTOs\v1\management\supports\StatusDTO;
use Illuminate\Foundation\Http\FormRequest;

class StatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|between:5,50',
            'badge' => 'nullable|string|between:5,50',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'name.string' => 'Nombre debe ser una cadena de texto.',
            'name.between' => 'Nombre debe tener entre 5 y 50 caracteres.',

            'badge.string' => 'Debe ser una cadena de texto.',
            'badge.between' => 'Debe tener entre 5 y 50 caracteres.',

            'status.required' => 'Estado es un campo obligatorio.',
            'status.integer' => 'Formato inválido.',
            'status.in' => 'El valor no es correcto.',
        ];
    }

    public function toDTO(): StatusDTO
    {
        return new StatusDTO(
            name: $this->input('name'),
            badge_color: $this->input('badge'),
            status_id: $this->input('status'),
        );
    }
}
