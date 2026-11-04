<?php

namespace App\Http\Requests\v1\Management\Infrastructure\Network;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\infrastructure\network\NodeContactDTO;

class NodeContactRequest extends FormRequest
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
            'node' => 'required|numeric',
            'name' => 'required|string',
            'phone' => 'required|regex:/^[267]\d{3}-\d{4}$/',
            'initial_date' => 'required|date',
            'final_date' => 'required|date',
            'status' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es requerido.',
            'name.string' => 'Formato inválido.',
            'phone.required' => 'Teléfono es requerido.',
            'phone.regex' => 'Formato incorrecto.',
            'initial_date.required' => 'Fecha de inicio es requerida.',
            'initial_date.date' => 'Fecha de inicio debe ser una fecha',
            'final_date.required' => 'Fecha de inicio es requerida.',
            'final_date.date' => 'Fecha de inicio debe ser una fecha',
        ];
    }

    public function toDTO(): NodeContactDTO
    {
        return new NodeContactDTO(
            node_id: $this->input('node'),
            name: $this->input('name'),
            phone_number: $this->input('phone'),
            initial_contract_date: Carbon::parse($this->input('initial_date')),
            final_contract_date: Carbon::parse($this->input('final_date')),
            status_id: $this->input('status')
        );
    }
}
