<?php

namespace App\Http\Requests\v1\Management\Infrastructure\Equipments;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\infrastructure\equipments\TypesDTO;

class TypesRequest extends FormRequest
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
            'name' => 'required|between:3,150',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'name.between' => 'Nombre debe tener entre 3 y 150 caracteres.',
        ];
    }

    public function toDTO(): TypesDTO
    {
        return new TypesDTO(
            name: $this->input('name'),
            status_id: $this->input('status'),
        );
    }
}
