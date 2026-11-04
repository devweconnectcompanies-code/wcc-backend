<?php

namespace App\Http\Requests\v1\Management\Services;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\services\ServiceEquipmentDTO;
use Illuminate\Validation\Rule;

class ServiceEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'equipment' => [
                'required',
                'integer',
                'exists:infrastructure_residential_equipment_inventory,id',
                Rule::unique('services_equipment', 'equipment_id')
                    ->ignore($this->route('id'))
                    ->whereNull('deleted_at')
            ],
            'service' => 'required|integer|exists:services,id',
        ];
    }

    public function messages(): array
    {
        return [
            'equipment.required' => 'No se ha seleccionado ningún equipo.',
            'equipment.integer' => 'Formato incorrecto.',
            'equipment.exists' => 'No se ha encontrado equipo.',
            'equipment.unique' => 'Este equipo ya ha sido asignado a otro servicio.',

            'service.required' => 'No se ha seleccionado ningún servicio.',
            'service.integer' => 'Formato incorrecto.',
            'service.exists' => 'No se ha encontrado servicio.',
        ];
    }

    public function toDTO(): ServiceEquipmentDTO
    {
        return new ServiceEquipmentDTO(
            equipment_id: $this->input('equipment'),
            service_id: $this->input('service'),
            status_id: 1
        );
    }
}
