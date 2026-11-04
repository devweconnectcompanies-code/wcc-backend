<?php

namespace App\Http\Requests\v1\Management\Billing\Payments;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'client' => 'required|integer|exists:clients,id',
            'invoices' => 'required|string',
            'amount' => 'required|numeric',
            'payment_method' => 'required|integer|exists:billing_payment_methods,id',
            'comments' => 'nullable|string|between:2,255',
            'discount' => 'nullable|int|exists:billing_discounts,id',
        ];
    }

    public function messages(): array
    {
        return [
            'client.required' => 'No se ha especificado el cliente.',
            'client.integer' => 'Formato incorrecto para cliente.',
            'client.exists' => 'El cliente seleccionado no existe.',

            'invoices.required' => 'Factura no especificada.',
            'invoices.integer' => 'Formato incorrecto para factura.',
            'invoices.exists' => 'La factura seleccionada no existe.',

            'amount.required' => 'El monto es obligatorio.',
            'amount.decimal' => 'El monto debe ser un número decimal.',

            'payment_method.required' => 'Método de pago no especificado.',
            'payment_method.integer' => 'Formato incorrecto.',
            'payment_method.exists' => 'Este método de pago no existe.',

            'comments.string' => 'Formato incorrecto para observaciones.',
            'comments.between' => 'Las observaciones deben tener entre 2 y 255 caracteres.',

            'discount.int' => 'Formato incorrecto.',
            'discount.exists' => 'El descuento seleccionado no existe.',
        ];
    }
}
