<?php

namespace App\Http\Requests\v1\Management\Clients;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\client\EmailDTO;
use Illuminate\Validation\Rule;

class EmailRequest extends FormRequest
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
            'client' => 'required|numeric',
            'email' => ['required', 'email', 'between:10,150', Rule::unique('clients_emails', 'email')->ignore($this->route('id'))],
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'client.required' => 'ID de cliente es requerido.',
            'client.integer' => 'ID de cliente debe ser un número entero.',
            'email.required' => 'Correo electrónico es un campo obligatorio.',
            'email.email' => 'Formato inválido de correo electrónico.',
            'email.between' => 'El correo electrónico debe tener entre :min y :max caracteres.',
            'email.unique' => 'Este correo ya ha sido previamente registrado.',
            'status.required' => 'Estado es requerido.',
            'status.boolean' => 'El estado debe ser un booleano.',
        ];
    }

    public function toDTO(): EmailDTO
    {
        return new EmailDTO(
            client_id: $this->input('client'),
            email: $this->input('email'),
            status_id: $this->input('status'),
        );
    }
}
