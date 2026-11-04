<?php

namespace App\Http\Requests\v1\Management\Accounting\Options;

use App\DTOs\v1\management\accounting\dte\events\EventDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|between:5,50',
            'code' => [
                'required',
                'between:2,5',
                Rule::unique('accounting_dte_event_types', 'code')->ignore($this->route('id')),
            ],
            'color' => 'required|size:7',
            'status' => 'required|integer|in:0,1',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'name.between' => 'El nombre debe tener entre 5 y 50 caracteres.',

            'code.required' => 'Código es un campo obligatorio.',
            'code.between' => 'El código debe tener entre 2 y 5 caracteres.',
            'code.unique' => 'Este código ya se encuentra registrado.',

            'color.required' => 'Color es un campo obligatorio.',
            'color.size' => 'El color debe contener 7 caracteres.',

            'status.required' => 'Estado es un campo obligatorio.',
            'status.integer' => 'Formato incorrecto para estado.',
            'status.in' => 'El valor no es correcto.',
        ];
    }

    /**
     * @return EventDTO
     */
    public function toDTO(): EventDTO
    {
        return new EventDTO(
            name: $this->input('name'),
            code: $this->input('code'),
            badge_color: $this->input('color'),
            status: $this->input('status'),
        );
    }
}
