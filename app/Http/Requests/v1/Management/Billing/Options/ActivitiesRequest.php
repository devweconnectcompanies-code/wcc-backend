<?php

namespace App\Http\Requests\v1\Management\Billing\Options;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\billing\options\ActivityDTO;
use Illuminate\Validation\Rule;

class ActivitiesRequest extends FormRequest
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
                Rule::unique('billing_activity_categories', 'code')
                    ->ignore($this->route('id'))
            ],
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'Formato inválido.',
            'code.required' => 'El campo código es obligatorio.',
            'code.string' => 'Formato inválido.',
            'code.unique' => 'Este código ya ha sido registrado.',
            'status.required' => 'El campo estado es obligatorio.',
            'status.boolean' => 'Formato no válido.',
        ];
    }

    public function toDTO(): ActivityDTO
    {
        return new ActivityDTO(
            name: $this->input('name'),
            code: $this->input('code'),
            status_id: $this->input('status'),
        );
    }
}
