<?php

namespace App\Http\Requests\v1\Management\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class StatesRequest extends FormRequest
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
            'code' => 'required|string',
            'iso' => 'required|string',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es requerido',
            'name.string' => 'Nombre debe ser una cadena',
            'code.required' => 'Codigo es requerido',
            'code.string' => 'Codigo debe ser una cadena',
            'iso.required' => 'Codigo ISO es requerido',
            'iso.string' => 'Codigo debe ser una cadena',
            'status.required' => 'Estado es requerido',
            'status.boolean' => 'Estado desconocido',
        ];
    }
}
