<?php

namespace App\Http\Requests\v1\Management\Clients;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\client\ContractDTO;

class ContractRequest extends FormRequest
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
            'start_contract' => 'required|date',
            'end_contract' => 'required|date',
            'installation_price' => 'required|numeric',
            'contract_amount' => 'required|numeric',
            'contract_status' => 'required|numeric',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'start_contract.required' => 'Fecha de inicio es un campo obligatorio.',
            'start_contract.date' => 'Formato incorrecto.',
            'end_contract.required' => 'Fecha final es un campo obligatorio.',
            'end_contract.date' => 'Formato incorrecto.',
            'installation_price.required' => 'Costo de instalación es un campo obligatorio.',
            'installation_price.numeric' => 'Formato incorrecto.',
            'contract_amount.required' => 'Costo total es un campo obligatorio.',
            'contract_amount.numeric' => 'Formato incorrecto.',
            'contract_status.required' => 'Estado es un campo obligatorio.',
            'contract_status.numeric' => 'Formato incorrecto.',
        ];
    }

    public function toDTO(): ContractDTO
    {
        return new ContractDTO(
            client_id: $this->input('client'),
            contract_date: Carbon::parse($this->input('start_contract')),
            contract_end_date: Carbon::parse($this->input('end_contract')),
            installation_price: $this->input('installation_price'),
            contract_amount: $this->input('contract_amount'),
            contract_status_id: $this->input('contract_status'),
            status_id: $this->input('status'),
        );
    }
}
