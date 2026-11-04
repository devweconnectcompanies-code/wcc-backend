<?php

namespace App\Http\Requests\v1\Management\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\DTOs\v1\management\admin\users\UserDTO;

class UsersRequest extends FormRequest
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
        $id = $this->route('id') ?? $this->input('id');

        return [
            'name' => 'required|string|min:10',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'password' => $this->isMethod('POST') ? ['required', 'string', 'min:8'] : ['nullable', 'string', 'min:8'],
            'status' => 'required|boolean',
            'role' => ['required', Rule::exists('roles', 'id')],
            'permissions' => 'required|array',
            'permissions.*' => ['required', 'integer', Rule::exists('permissions', 'id')]
        ];

    }

    public function messages(): array
    {
        return [
            'name.required' => 'Debes ingresar el nombre del usuario.',
            'name.string' => 'Formato de nombre incorrecto.',
            'name.min' => 'Nombre de usuario debe tener 10 caracteres como mínimo.',
            'email.required' => 'Debes ingresar el email del usuario.',
            'email.email' => 'Formato de email incorrecto.',
            'email.unique' => 'Este email ya fue registrado.',
            'password.required' => 'Contraseña es un campo obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'status.required' => 'Debes ingresar el estado del usuario.',
            'status.boolean' => 'Debes seleccionar un valor valido.',
            'role.required' => 'Debes seleccionar el rol del usuario.',
            'role.exists' => 'El rol seleccionado no existe.',
            'permissions.required' => 'Debes seleccionar los permisos del usuario.',
            'permissions.array' => 'Formato inválido.',
            'permissions.*.exists' => 'Uno o más permisos no existen.',
            'permissions.*.integer' => 'Los identificadores de permisos deben ser enteros.',
        ];
    }

    public function toDTO(): UserDTO
    {
        return new UserDTO(
            name: $this->input('name'),
            email: $this->input('email'),
            password: $this->input('password'),
            status_id: $this->input('status'),
            role: $this->input('role', 2),
            permissions: $this->input('permissions', [])
        );
    }
}
