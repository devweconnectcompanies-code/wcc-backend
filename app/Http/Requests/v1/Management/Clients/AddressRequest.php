<?php

namespace App\Http\Requests\v1\Management\Clients;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\client\AddressDTO;

class AddressRequest extends FormRequest
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
            'neighborhood' => 'required|string',
            'address' => 'required|between:10,255',
            'state' => 'required|integer',
            'municipality' => 'required|integer',
            'district' => 'required|integer',
            'country' => 'required|integer',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'neighborhood.required' => 'Barrio/Colonia es un campo obligatorio.',
            'neighborhood.string' => 'Formato incorrecto.',
            'address.required' => 'Dirección es un campo obligatorio.',
            'address.between' => 'Dirección debe contener entre :min y :max caracteres.',
        ];
    }

    public function toDTO(): AddressDTO
    {
        return new AddressDTO(
            client_id: $this->input('client'),
            neighborhood: $this->input('neighborhood'),
            address: $this->input('address'),
            state_id: $this->input('state'),
            municipality_id: $this->input('municipality'),
            district_id: $this->input('district'),
            country_id: $this->input('country'),
            status_id: $this->input('status'),
        );
    }
}
