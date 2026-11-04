<?php

namespace App\Http\Requests\v1\Management\Configuration;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\configuration\branches\BranchDTO;

class BranchesRequest extends FormRequest
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
            'name' => 'required|string',
            'code' => 'nullable|string',
            'landline' => 'nullable|string',
            'mobile' => 'required|string',
            'address' => 'required|string',
            'state' => 'required|numeric',
            'municipality' => 'required|numeric',
            'district' => 'nullable|numeric',
            'country' => 'required|numeric',
            'badge' => 'nullable|string',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'code.string' => 'El campo código debe ser una cadena de texto.',
            'landline.string' => 'Formato inválido para teléfono fijo.',
            'mobile.required' => 'El campo teléfono móvil es obligatorio.',
            'mobile.string' => 'Formato inválido para teléfono móvil.',
            'address.required' => 'Direccióne es un campo obligatorio.',
            'address.string' => 'Formato inválido para dirección.',
            'state.required' => 'El campo departamento es obligatorio.',
            'state.numeric' => 'Formato inválido para departamento.',
            'municipality.required' => 'El campo municipio es obligatorio.',
            'municipality.numeric' => 'Formato inválido para municipio.',
            'district.required' => 'El campo distrito es obligatorio.',
            'district.numeric' => 'Formato incorrecto para distrito.',
            'country.required' => 'El campo país es obligatorio.',
            'country.numeric' => 'Formato inválido para país.',
            'badge.string' => 'Formato incorrecto para color',
            'status.required' => 'El campo estado es obligatorio.',
            'status.boolean' => 'Formato incorrecto para estado.',
        ];
    }

    public function toDTO(): BranchDTO
    {
        return new BranchDTO(
            name: $this->input('name'),
            code: $this->input('code'),
            landline: $this->input('landline'),
            mobile: $this->input('mobile'),
            address: $this->input('address'),
            state_id: $this->input('state'),
            municipality_id: $this->input('municipality'),
            district_id: $this->input('district'),
            country_id: $this->input('country'),
            badge_color: $this->input('badge'),
            status_id: $this->input('status'),
        );
    }
}
