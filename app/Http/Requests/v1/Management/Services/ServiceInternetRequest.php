<?php

namespace App\Http\Requests\v1\Management\Services;

use App\Models\Services\ServiceInternetModel;
use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\services\ServiceInternetDTO;
use Illuminate\Validation\Rule;

class ServiceInternetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile' => ['required', 'integer', 'exists:management_internet_profiles,id'],
            'service' => 'required|integer|exists:services,id',
            'user' => [
                'required',
                'string',
                Rule::unique('internet_services', 'user')
                    ->ignore($this->route('id'))
            ],
            'password' => ['required', 'string', 'between:8,25'],
            'status' => ['required', 'in:1,0']
        ];
    }

    public function messages(): array
    {
        return [
            'profile.required' => 'Perfil de internet es obligatorio.',
            'profile.integer' => 'Formato no permitido.',
            'profile.exists' => 'Perfil de internet es inválido.',

            'service.required' => 'ID de servicio es obligatorio.',
            'service.integer' => 'Formato no permitido.',
            'service.exists' => 'Servicio seleccionado no existe.',

            'user.required' => 'Usuario es obligatorio.',
            'user.string' => 'Formato no permitido.',
            'user.unique' => 'Este usuario ya existe.',

            'password.required' => 'Password es obligatorio.',
            'password.string' => 'Formato no permitido.',
            'password.between' => 'Password debe tener entre 8 y 25 caracteres.',

            'status.required' => 'Estado es obligatorio.',
            'status.in' => 'Estado seleccionado no existe.',
        ];
    }


    public function toDTO(): ServiceInternetDTO
    {
        return new ServiceInternetDTO(
            internet_profile_id: $this->input('profile'),
            service_id: $this->input('service'),
            user: $this->input('user'),
            secret: $this->input('password'),
            status_id: $this->input('status'),
        );
    }
}
