<?php

namespace App\Http\Requests\v1\Management\Configuration;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\configuration\clients\PhoneTypeDTO;

class PhoneTypeRequest extends FormRequest
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
            'name' => 'required|string|between:3,50',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'name.string' => 'Campo nombre debe ser una cadena de texto.',
            'name.between' => 'Campo nombre debe tener entre :min y :max caracteres.',
            'status.required' => 'Estado es un campo obligatorio.',
            'status.boolean' => 'Formato inválido para estado.',
        ];
    }

    public function toDTO(): PhoneTypeDTO
    {
        return new PhoneTypeDTO(
            name: $this->input('name'),
            status_id: $this->input('status'),
        );
    }
}
