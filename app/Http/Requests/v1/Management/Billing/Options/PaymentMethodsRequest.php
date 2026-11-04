<?php

namespace App\Http\Requests\v1\Management\Billing\Options;

use App\DTOs\v1\management\billing\options\PaymentMethodDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentMethodsRequest extends FormRequest
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
                'between:2,15',
                Rule::unique('billing_payment_methods', 'code')->ignore($this->route('id')),
            ],
            'color' => 'required|size:7',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'name.between' => 'El nombre debe contener entre 5 y 50 caracteres.',

            'code.required' => 'Código es un campo obligatorio.',
            'code.between' => 'El código debe contener entre 2 y 15 caracteres.',
            'code.unique' => 'Este código ya se encuentra registrado.',

            'color.required' => 'Color es un campo obligatorio.',
            'color.size' => 'El campo color debe contener 7 caracteres.',

            'status.required' => 'Estado es un campo obligatorio.',
            'status.integer' => 'Formato incorrecto.',
            'status.in' => 'Formato desconocido.',
        ];
    }

    public function toDTO(): PaymentMethodDTO
    {
        return new PaymentMethodDTO(
            name: $this->input('name'),
            code: $this->input('code'),
            badge_color: $this->input('color'),
            status_id: $this->input('status'),
        );
    }
}
