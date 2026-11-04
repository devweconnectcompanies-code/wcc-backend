<?php

namespace App\Http\Requests\v1\Management\Billing\Options;

use App\DTOs\v1\management\billing\options\DiscountDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|between:5,50',
            'code' => [
                'required',
                'between:5,50',
                Rule::unique('billing_discounts', 'code')->ignore($this->route('id')),
            ],
            'description' => 'nullable|between:5,255',
            'percentage' => 'nullable|numeric|decimal:2',
            'amount' => 'nullable|numeric|decimal:2',
            'status' => 'required|numeric|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo requerido.',
            'name.between' => 'Nombre debe tener entre 5 y 50 caracteres.',

            'code.required' => 'Código es un campo requerido.',
            'code.between' => 'El código debe contener entre 5 y 50 caracteres.',
            'code.unique' => 'Este código ya está siendo utilizado.',

            'description.between' => 'La descripción debe tener entre 5 y 255 caracteres.',

            'percentage.numeric' => 'Porcentaje debe ser un número.',
            'percentage.decimal' => 'El porcentaje debe ser un número decimal.',

            'amount.numeric' => 'Monto fijo debe ser un número.',
            'amount.decimal' => 'Monto fijo debe ser decimal.',
        ];
    }

    public function toDTO(): DiscountDTO
    {
        return new DiscountDTO(
            name: $this->input('name'),
            code: $this->input('code'),
            description: $this->input('description'),
            percentage: $this->input('percentage'),
            amount: $this->input('amount'),
            status_id: $this->input('status'),
        );
    }
}
