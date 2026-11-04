<?php

namespace App\Http\Requests\v1\Management\Admin\Users;

use App\DTOs\v1\management\admin\users\PermissionDTO;
use Illuminate\Foundation\Http\FormRequest;

class PermissionsRequest extends FormRequest
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
            'name' => 'required|between:3,254|unique:permissions,name',
            'menu' => ['required', 'integer', 'exists:config_menu,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.between' => 'El campo nombre debe tener entre 3 y 254 caracteres.',
            'name.unique' => 'Este permiso ya existe.',
            'menu.required' => 'El campo menu es obligatorio.',
            'menu.integer' => 'El campo menu debe ser un entero.',
            'menu.exists' => 'El campo menu no existe.',
        ];
    }

    public function toDTO(): PermissionDTO
    {
        return new PermissionDTO(
            name: $this->input('name'),
            guard_name: 'web',
            menu_id: $this->input('menu'),
        );
    }
}
