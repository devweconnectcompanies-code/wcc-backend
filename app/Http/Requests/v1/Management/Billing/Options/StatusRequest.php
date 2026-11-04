<?php

namespace App\Http\Requests\v1\Management\Billing\Options;

use App\DTOs\v1\management\billing\options\StatusesDTO;
use Illuminate\Foundation\Http\FormRequest;

class StatusRequest extends FormRequest
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
            'name' => ['required', 'string', 'between:3,25'],
            'color' => ['required', 'string', 'between:3,25'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'name.string' => 'Formato incorrecto.',
            'name.between' => 'El nombre debe tener entre 3 y 25 caracteres.',

            'color.required' => 'Color es un campo obligatorio.',
            'color.string' => 'Formato incorrecto.',
            'color.between' => 'El color debe tener entre 3 y 25 caracteres.',

            'status.required' => 'Estado es un campo obligatorio.',
            'status.integer' => 'Formato incorrecto.',
            'status.in' => 'Estado indefinido.',
        ];
    }

    public function toDTO(): StatusesDTO
    {
        return new StatusesDTO(
            name: $this->input('name'),
            badge_color: $this->input('color'),
            status_id: $this->input('status'),
        );
    }
}
