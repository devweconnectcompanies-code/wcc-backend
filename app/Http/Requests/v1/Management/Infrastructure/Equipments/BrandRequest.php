<?php

namespace App\Http\Requests\v1\Management\Infrastructure\Equipments;

use App\DTOs\v1\management\infrastructure\equipments\BrandDTO;
use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            'name' => 'required|string|between:3,100',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Campo nombre es obligatorio.',
            'name.string' => 'Campo nombre debe ser una cadena.',
            'name.between' => 'Campo nombre debe tener entre 3 y 100 caracteres.',
        ];
    }

    public function toDTO(): BrandDTO
    {
        return new BrandDTO(
            name: $this->input('name'),
            status_id: $this->input('status'),
        );
    }
}
