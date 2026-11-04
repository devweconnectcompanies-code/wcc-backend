<?php

namespace App\Http\Requests\v1\Management\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class MunicipalitiesRequest extends FormRequest
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
            'state' => 'required|integer',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'name.string' => 'El campo nombre debe ser un texto',
            'code.required' => 'El campo codigo es obligatorio',
            'code.string' => 'El campo codigo debe ser un texto',
            'state.required' => 'El campo Departamento es obligatorio',
            'state.integer' => 'El campo Departamento debe ser un entero',
            'status.required' => 'El campo Estado es obligatorio',
            'status.boolean' => 'Formato del campo Estado incorrecto',
        ];
    }
}
