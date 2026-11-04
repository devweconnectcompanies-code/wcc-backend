<?php

namespace App\Http\Requests\v1\Management\Clients;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\client\PhoneDTO;
use Illuminate\Validation\Rule;

class PhoneRequest extends FormRequest
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
            'client' => 'required|integer',
            'type' => 'required|integer',
            'country' => 'required',
            'number' => ['required', Rule::unique('clients_phones', 'number')->ignore($this->route('id'))],
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'client.required' => 'ID de cliente requerido.',
            'client.integer' => 'Formato de cliente incorrecto.',
            'type.required' => 'Tipo de teléfono requerido.',
            'type.integer' => 'Formato de tipo incorrecto.',
            'country.required' => 'País es requerido.',
            'number.required' => 'Número de teléfono es un campo obligatorio.',
            'number.unique' => 'Este número ya ha sido registrado.',
            'status.required' => 'Estado es un campo requerido.',
            'status.boolean' => 'Formato de estado incorrecto.',
        ];
    }

    public function toDTO(): PhoneDTO
    {
        return new PhoneDTO(
            client_id: $this['client'],
            phone_type_id: $this['type'],
            number: $this['number'],
            status_id: $this['status'],
            country_code: $this['country'],
        );
    }
}
