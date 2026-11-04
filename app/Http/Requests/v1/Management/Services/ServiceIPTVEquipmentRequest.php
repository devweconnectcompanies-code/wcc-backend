<?php

namespace App\Http\Requests\v1\Management\Services;

use App\DTOs\v1\management\services\ServiceIptvEquipmentDTO;
use App\Models\Services\ServiceIptvEquipmentModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ServiceIPTVEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'equipment' => 'required|integer|exists:infrastructure_residential_equipment_inventory,id',
            'service' => 'required|integer|exists:services,id',
            'email' => 'required|email',
            'email_password' => 'required|min:8',
            'iptv_password' => 'required|min:8',
            'comments' => 'nullable|between:10,254',
//            'status' => 'required|integer|in:0,1',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $email = $this->input('email');

            if ($email) {
                $query = ServiceIptvEquipmentModel::query()
                    ->where('email', $email)
                    ->whereNull('deleted_at');

                if ($this->route('id')) {
                    $query->whereKeyNot($this->route('id'));
                }

                $count = $query->count();

                if ($count >= 3) {
                    $validator->errors()->add('email', 'Este correo ya ha sido registrado en 3 equipos y no puede ser asignado nuevamente.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'equipment.required' => 'No se ha adjuntado el equipo.',
            'equipment.integer' => 'Formato incorrecto.',
            'equipment.exists' => 'Este equipo no existe.',

            'service.required' => 'No se ha adjuntado el servicio.',
            'service.integer' => 'Formato incorrecto.',
            'service.exists' => 'Este servicio no existe.',

            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'Formato de email incorrecto.',

            'email_password.required' => 'El campo contraseña del correo es obligatorio.',
            'email_password.min' => 'La contraseña debe tener al menos 8 caracteres.',

            'iptv_password.required' => 'Contraseña de IPTV es un campo obligatorio.',
            'iptv_password.min' => 'La contraseña de IPTV debe tener al menos 8 caracteres.',

            'comments.between' => 'Comentarios debe contener entre 10 y 254 caracteres.',

            'status.required' => 'El campo estado es obligatorio.',
            'status.integer' => 'Formato incorrecto.',
            'status.in' => 'No se reconoce el valor enviado.',
        ];
    }

    public function toDTO(): ServiceIptvEquipmentDTO
    {
        return new ServiceIptvEquipmentDTO(
            equipment_id: $this->input('equipment'),
            service_id: $this->input('service'),
            email: $this->input('email'),
            email_password: $this->input('email_password'),
            iptv_password: $this->input('iptv_password'),
            comments: $this->input('comments') ?? null,
            status_id: /*$this->input('status')*/ 1,
        );
    }
}
