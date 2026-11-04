<?php

namespace App\Http\Requests\v1\Management\Configuration;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\configuration\geography\DistrictsDTO;

class DistrictsRequest extends FormRequest
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
            'municipality' => 'required|integer',
            'state' => 'required|integer',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'name.string' => 'El campo nombre debe ser un texto',
            'code.required' => 'El campo código es obligatorio',
            'code.string' => 'El campo código debe ser un texto',
            'municipality.required' => 'El campo municipio es obligatorio',
            'municipality.integer' => 'El campo municipio debe ser un número',
            'state.required' => 'El campo departamento es obligatorio',
            'state.integer' => 'Formato inválido para departamento',
            'status.required' => 'El campo status es obligatorio',
            'status.boolean' => 'Formato inválido para estado',
        ];
    }

    public function toDto(): DistrictsDTO
    {
        return new DistrictsDTO(
            name: $this->input('name'),
            municipality_id: $this->input('municipality'),
            state_id: $this->input('state'),
            status_id: $this->input('status'),
            code: $this->input('code'),
        );
    }
}
