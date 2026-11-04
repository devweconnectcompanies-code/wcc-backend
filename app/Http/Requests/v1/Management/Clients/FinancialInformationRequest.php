<?php

namespace App\Http\Requests\v1\Management\Clients;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\client\FinancialInformationDTO;
use Illuminate\Validation\Rule;

class FinancialInformationRequest extends FormRequest
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
            'client' => 'required|numeric',
            'nrc' => [
                'required',
                'regex:/^\d{6}-\d$/',
                Rule::unique('clients_financial_information', 'nrc')->ignore($this->route('id'))
            ],
            'activity' => 'required|numeric',
            'retained' => 'required|boolean',
            'representative' => 'required|string',
            'dui' => [
                'required',
                'regex:/^\d{8}-\d$/',
                Rule::unique('clients_financial_information', 'dui')->ignore($this->route('id'))
            ],
            'nit' => [
                'required',
                'regex:/^\d{8}-\d$|^\d{4}-\d{6}-\d{3}-\d$/',
                Rule::unique('clients_financial_information', 'nit')->ignore($this->route('id'))
            ],
            'phone' => 'required|regex:/^[267]\d{3}-\d{4}$/',
            'alias' => 'nullable|string',
            'state' => 'required|numeric',
            'municipality' => 'required|numeric',
            'district' => 'required|numeric',
            'address' => 'required|string|between:10,255',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nrc.required' => 'NRC es un campo obligatorio.',
            'nrc.regex' => 'Formato inválido.',
            'nrc.unique' => 'Este NRC ya ha sido registrado.',
            'representative.required' => 'Representante legal es un campo obligatorio.',
            'representative.string' => 'Formato inválido.',
            'dui.required' => 'DUI es un campo obligatorio.',
            'dui.regex' => 'Formato inválido.',
            'dui.unique' => 'Este DUI ya ha sido registrado.',
            'nit.required' => 'NIT es un campo obligatorio.',
            'nit.regex' => 'Formato inválido.',
            'nit.unique' => 'Este NIT ya ha sido registrado.',
            'phone.required' => 'Telefono es un campo obligatorio.',
            'phone.regex' => 'Formato incorrecto.',
//            'alias.string' => 'Formato incorrecto.',
            'address.required' => 'Dirección es un campo obligatorio.',
            'address.string' => 'Formato incorrecto.',
            'address.between' => 'La dirección debe contener entre :min y :max caracteres.',
        ];
    }

    public function toDTO(): FinancialInformationDTO
    {
        return new FinancialInformationDTO(
            client_id: $this->input('client'),
            nrc: $this->input('nrc'),
            activity_id: $this->input('activity'),
            retained_iva: $this->input('retained'),
            legal_representative: $this->input('representative'),
            dui: $this->input('dui'),
            nit: $this->input('nit'),
            phone_number: $this->input('phone'),
            invoice_alias: $this->input('alias') ?? null,
            state_id: $this->input('state'),
            municipality_id: $this->input('municipality'),
            district_id: $this->input('district'),
            address: $this->input('address'),
            status_id: $this->input('status'),
        );
    }
}
