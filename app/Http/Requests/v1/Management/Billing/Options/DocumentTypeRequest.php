<?php

namespace App\Http\Requests\v1\Management\Billing\Options;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\billing\options\DocumentTypeDTO;
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
            'code' => ['required', 'string', Rule::unique('billing_document_types', 'code')->ignore($this->route('id'))],
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es un campo obligatorio.',
            'name.string' => 'El nombre debe ser un texto.',
            'code.required' => 'Código es un campo obligatorio.',
            'code.string' => 'Formato inválido para código.',
            'code.unique' => 'Este código ya ha sido registrado.',
            'status.required' => 'El estado es un campo obligatorio.',
            'status.boolean' => 'El estado debe ser un booleano.',
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
