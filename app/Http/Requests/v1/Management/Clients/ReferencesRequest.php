<?php

namespace App\Http\Requests\v1\Management\Clients;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\client\ReferenceDTO;

class ReferencesRequest extends FormRequest
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
            'client' => 'required|integer',
            'name' => 'required|string|between:10,100',
            'dui' => 'required|string|size:10',
            'mobile' => 'required|string',
            'kinship' => 'required|integer',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre de referencia es requerido.',
            'name.string' => 'Formato de referencia no válido.',
            'name.between' => 'El nombre debe conter entre :min y :max caracteres.',
            'dui.required' => 'DUI de referencia es requerido.',
            'dui.string' => 'Formato de DUI incorrecto.',
            'dui.size' => 'El DUI debe contener 10 caracteres.',
            'mobile.required' => 'Teléfono de referencia es requerido.',
            'mobile.string' => 'Formato de teléfono incorrecto.',
        ];
    }

    public function toDTO(): ReferenceDTO
    {
        return new ReferenceDTO(
            client_id: $this->input('client'),
            name: $this->input('name'),
            dui: $this->input('dui'),
            mobile: $this->input('mobile'),
            kinship_id: $this->input('kinship'),
            status_id: $this->input('status'),
        );
    }
}
