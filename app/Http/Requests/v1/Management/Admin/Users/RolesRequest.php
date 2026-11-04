<?php

namespace App\Http\Requests\v1\Management\Admin\Users;

use App\DTOs\v1\management\admin\users\RoleDTO;
use Illuminate\Foundation\Http\FormRequest;

class RolesRequest extends FormRequest
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
            'name' => 'required|between:3,254',
            'homepage' => 'required|between:3,254',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',     // Evalua que cada elemento del array sea valido
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es requerido.',
            'name.between' => 'Nombre debe tener entre 3 y 254 caracteres.',
            'homepage.required' => 'URL de redirección es requerida.',
            'homepage.between' => 'URL debe tener entre 3 y 254 caracteres.',
        ];
    }

    public function toDTO(): RoleDTO
    {
        return new RoleDTO(
            name: $this->input('name'),
            guard_name: 'web',
            homepage: $this->input('homepage'),
            permissions: $this->input('permissions', []),
        );
    }
}
