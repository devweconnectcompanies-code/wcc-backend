<?php

namespace App\Http\Requests\v1\Management\Admin\Users;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\DTOs\v1\management\admin\users\TechnicianDTO;

class TechniciansRequest extends FormRequest
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
            'user' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^[267]\d{3}-\d{4}$/',
                Rule::unique('technicians', 'phone_number')->ignore($this->route('id'))
            ],
            'hiring_date' => 'required|date',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user.required' => 'El campo usuario es obligatorio.',
            'user.integer' => 'Formato incorrecto.',
            'user.exists' => 'El usuario seleccionado no existe.',
            'phone.required' => 'El campo teléfono es obligatorio.',
            'phone.regex' => 'Formato incorrecto.',
            'phone.unique' => 'Este teléfono ya se encuentra registrado.',
            'hiring_date.required' => 'El campo fecha de contratación es obligatorio.',
            'hiring_date.date' => 'No es una fecha valida.',
            'status.required' => 'El campo estado es obligatorio.',
            'status.boolean' => 'Formato inválido.',
        ];
    }

    public function toDTO(): TechnicianDTO
    {
        return new TechnicianDTO(
            user_id: $this->input('user'),
            phone_number: $this->input('phone'),
            status_id: $this->input('status'),
            hiring_date: Carbon::parse($this->input('hiring_date')),
        );
    }
}
