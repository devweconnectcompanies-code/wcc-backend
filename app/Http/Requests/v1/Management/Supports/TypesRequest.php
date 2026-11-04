<?php

namespace App\Http\Requests\v1\Management\Supports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\DTOs\v1\management\supports\TypesDTO;

class TypesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'between:5,50',
                Rule::unique('supports_types', 'name')->ignore($this->route('id'))
            ],
            'badge' => 'nullable|string|between:5,50',
            'status' => 'required|integer|in:0,1',
            'price' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'name.string' => 'Formato de nombre incorrecto.',
            'name.between' => 'Nombre debe tener entre 5 y 50 caracteres.',
            'name.unique' => 'Este nombre ya existe en la base de datos.',

            'badge.string' => 'Formato de color incorrecto.',
            'badge.between' => 'Debe contener entre 5 y 50 caracteres.',

            'status.required' => 'Estado es un campo obligatorio.',
            'status.integer' => 'Formato de estado incorrecto.',
            'status.in' => 'Este estado no es valido.',

            'price.required' => 'Precio es un campo obligatorio.',
            'price.numeric' => 'Precio debe ser un número decimal.',
        ];
    }

    public function toDTO(): TypesDTO
    {
        return new TypesDTO(
            name: $this->input('name'),
            badge_color: $this->input('badge') ?? null,
            status_id: $this->input('status'),
            price: $this->input('price'),
        );
    }
}
