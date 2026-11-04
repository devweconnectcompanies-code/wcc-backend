<?php

namespace App\Http\Requests\v1\Management\Configuration;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\configuration\clients\ContractStatusDTO;

class ContractStatusRequest extends FormRequest
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
            'name' => 'required|between:3,60',
            'badge' => 'required|string',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es requerido',
            'name.between' => 'Nombre debe tener entre :min y :max caracteres',
            'badge.required' => 'Color es requerido',
            'badge.string' => 'Formato de color no válido',
        ];
    }

    public function toDTO(): ContractStatusDTO
    {
        return new ContractStatusDTO(
            name: $this->input('name'),
            status_id: $this->input('status'),
            badge_color: $this->input('badge'),
        );
    }
}
