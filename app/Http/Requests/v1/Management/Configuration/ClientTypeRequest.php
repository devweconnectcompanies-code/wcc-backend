<?php

namespace App\Http\Requests\v1\Management\Configuration;

use App\DTOs\v1\management\configuration\clients\ClientTypeDTO;
use Illuminate\Foundation\Http\FormRequest;

class ClientTypeRequest extends FormRequest
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
            'status' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es requerido',
            'name.string' => 'Nombre debe ser un texto',
            'status.required' => 'Estado es requerido',
            'status.boolean' => 'Formato inválido para estado'
        ];
    }

    public function toDTO(): ClientTypeDTO
    {
        return new ClientTypeDTO(
            name: $this->input('name'),
            status_id: $this->input('status'),
        );
    }
}
