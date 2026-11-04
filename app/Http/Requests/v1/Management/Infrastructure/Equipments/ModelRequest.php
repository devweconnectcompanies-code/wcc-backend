<?php

namespace App\Http\Requests\v1\Management\Infrastructure\Equipments;

use App\DTOs\v1\management\infrastructure\equipments\ModelDTO;
use Illuminate\Foundation\Http\FormRequest;

class ModelRequest extends FormRequest
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
            'type' => 'required|integer',
            'brand' => 'required|integer',
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

    public function toDTO(): ModelDTO
    {
        return new ModelDTO(
            name: $this->input('name'),
            equipment_type_id: $this->input('type'),
            brand_id: $this->input('brand'),
            status_id: $this->input('status'),
        );
    }
}
