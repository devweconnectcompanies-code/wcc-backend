<?php

namespace App\Http\Requests\v1\Management\Infrastructure\Network;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\infrastructure\network\AuthServerDTO;

class AuthServersRequest extends FormRequest
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
            'name' => 'required',
            'user' => 'required',
            'secret' => 'required',
            'ip' => 'required|ipv4',
            'port' => 'required|numeric',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'user.required' => 'Usuario es un campo obligatorio.',
            'secret.required' => 'Secret es un campo obligatorio.',
            'ip.required' => 'IP es un campo obligatorio.',
            'ip.ipv4' => 'Formato de IP incorrecto.',
            'port.required' => 'Port es un campo obligatorio.',
            'port.numeric' => 'Formato inválido'
        ];
    }

    public function toDTO(): AuthServerDTO
    {
        return new AuthServerDTO(
            name: $this->input('name'),
            user: $this->input('user'),
            secret: $this->input('secret'),
            ip: $this->input('ip'),
            port: $this->input('port'),
            status_id: $this->input('status')
        );
    }
}
