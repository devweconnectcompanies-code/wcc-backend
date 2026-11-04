<?php

namespace App\Http\Requests\v1\Management\Configuration\Infrastructure;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\configuration\infrastructure\equipment\EquipmentStatusDTO;

class EquipmetStatusRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required|between:3,200',
            'badge' => 'required',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es requerido.',
            'description.required' => 'Descripción es un campo requerido.',
            'description.between' => 'La descripción debe tener entre 3 y 200 caracteres.',
            'badge.required' => 'Color es requerido.',
        ];
    }

    public function toDTO(): EquipmentStatusDTO
    {
        return new EquipmentStatusDTO(
            name: $this->input('name'),
            badge_color: $this->input('badge'),
            status_id: $this->input('status'),
            description: $this->input('description'),
        );
    }
}
