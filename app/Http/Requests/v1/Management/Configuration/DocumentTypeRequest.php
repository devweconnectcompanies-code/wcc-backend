<?php

namespace App\Http\Requests\v1\Management\Configuration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\configuration\clients\DocumentTypeDTO;
use Illuminate\Validation\Rule;

class DocumentTypeRequest extends FormRequest
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
            'code' => [
                'required',
                'string',
                Rule::unique('config_document_types', 'code')->ignore($this->route('id'))
            ],
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre de documento es requerido.',
            'name.string' => 'Nombre de documento no tiene el formato correcto.',
            'code.required' => 'Código de documento es requerido.',
            'code.string' => 'Codigo de documento no tiene el formato correcto.',
            'code.unique' => 'Este código ya ha sido registrado.',
            'status.required' => 'Estado es requerido.',
            'status.boolean' => 'Estado no tiene el formato correcto.',
        ];
    }

    public function toDTO(): DocumentTypeDTO
    {
        return new DocumentTypeDTO(
            name: $this->input('name'),
            code: $this->input('code'),
            status_id: $this->input('status'),
        );
    }
}
