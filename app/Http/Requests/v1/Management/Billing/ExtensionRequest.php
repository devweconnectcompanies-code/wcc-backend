<?php

namespace App\Http\Requests\v1\Management\Billing;

use Illuminate\Foundation\Http\FormRequest;

class ExtensionRequest extends FormRequest
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
            'invoice' => 'required|integer',
            'previous_due_date' => 'required|date_format:Y-m-d',
            'days' => 'required|integer|between:1,5',
            'reason' => 'required|string|between:2,255',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'invoice.required' => 'Id de factura requerido.',
            'invoice.integer' => 'Id de factura debe ser un número.',

            'previous_due_date.required' => 'Fecha de vencimiento de la factura es un campo obligatorio.',
            'previous_due_date.date_format' => 'Fecha con formato invalido.',

            'days.required' => 'Días es un campo obligatorio.',
            'days.integer' => 'Días debe ser un número.',
            'days.between' => 'Solo se pueden asignar de 1 a 5 días.',

            'reason.required' => 'Motivo es un campo requerido.',
            'reason.string' => 'Formato incorrecto.',
            'reason.between' => 'El motivo debe contener entre 2 y 255 caracteres.',

            'status.required' => 'Estado es un campo obligatorio.',
            'status.integer' => 'Formato incorrecto.',
            'status.in' => 'Formato incorrecto.',
        ];
    }
}
