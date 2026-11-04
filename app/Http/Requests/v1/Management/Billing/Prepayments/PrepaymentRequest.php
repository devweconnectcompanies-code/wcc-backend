<?php

namespace App\Http\Requests\v1\Management\Billing\Prepayments;

use Illuminate\Foundation\Http\FormRequest;

class PrepaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client' => 'required|integer|exists:clients,id',
            'amount' => 'required|decimal:2',
            'payment_method' => 'required|integer|exists:billing_payment_methods,id',
//            'payment_date' => 'required|date',
            'reference' => 'nullable|string',
            'comments' => 'nullable|string',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'client.required' => 'Cliente es requerido.',
            'client.integer' => 'Formato incorrecto para cliente.',
            'client.exists' => 'El cliente seleccionado no existe.',

            'amount.required' => 'El monto del abono es requerido.',
            'amount.decimal' => 'El monto del abono debe ser decimal.',

            'payment_method.required' => 'Método de pago es requerido.',
            'payment_method.integer' => 'Formato incorrecto para método.',
            'payment_method.exists' => 'El método seleccionado no existe.',

//            'payment_date.required' => 'Fecha del pago es requerido.',
//            'payment_date.date' => 'Formato incorrecto para la fecha del pago.',

            'reference.string' => 'Formato incorrecto para número de referencia.',

            'comments.string' => 'Formato inválido para observaciones.',

            'status.required' => 'Estado es requerido.',
            'status.integer' => 'Formato incorrecto para estado.',
            'status.in' => 'El estado seleccionado no existe.',
        ];
    }
}
