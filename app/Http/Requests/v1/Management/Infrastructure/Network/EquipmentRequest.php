<?php

namespace App\Http\Requests\v1\Management\Infrastructure\Network;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\v1\management\infrastructure\network\EquipmentDTO;
use Illuminate\Validation\Rule;

class EquipmentRequest extends FormRequest
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
            'name' => 'required|between:3,150',
            'type' => 'required|exists:infrastructure_equipment_types,id',
            'brand' => 'required|exists:infrastructure_equipment_brands,id',
            'model' => 'required|exists:infrastructure_equipment_models,id',
            'mac' => [
                'required',
                'mac_address',
                Rule::unique('infrastructure_equipment', 'mac_address')
                    ->ignore($this->route('id'))
            ],
            'ip' => [
                'required',
                'ipv4',
                Rule::unique('infrastructure_equipment', 'ip_address')
                    ->ignore($this->route('id'))
            ],
            'username' => 'required|between:3,50',
            'secret' => 'required',
            'node' => 'required|exists:infrastructure_nodes,id',
            'comments' => 'nullable',
            'status' => 'required|exists:config_infrastructure_equipment_status,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nombre es un campo obligatorio.',
            'name.between' => 'Nombre debe tener entre 3 y 150 caracteres.',
            'type.required' => 'Tipo es un campo obligatorio.',
            'type.exists' => 'El tipo seleccionado es inválido.',
            'brand.required' => 'Marca es un campo obligatorio.',
            'brand.exists' => 'Marca seleccionada no existe.',
            'model.required' => 'Modelo es un campo obligatorio.',
            'model.exists' => 'Modelo seleccionada no existe.',
            'mac.required' => 'MAC es un campo obligatorio.',
            'mac.mac_address' => 'Formato inválido de MAC.',
            'mac.unique' => 'Esta mac ya ha sido registrada.',
            'ip.required' => 'IP es un campo obligatorio.',
            'ip.ipv4' => 'Formato incorrecto para IP.',
            'ip.unique' => 'Esta IP ya ha sido registrada.',
            'username.required' => 'Nombre de usuario es un campo obligatorio.',
            'username.between' => 'Nombre de usuario debe tener entre 3 y 50 caracteres.',
            'secret.required' => 'Contraseña es un campo obligatorio.',
            'node.required' => 'Nodo es un campo obligatorio.',
            'node.exists' => 'Nodo seleccionado no existe.',
            'status.required' => 'Estado es un campo obligatorio.',
            'status.exists' => 'Estado seleccionado no existe.',
        ];
    }

    public function toDTO(): EquipmentDTO
    {
        return new EquipmentDTO(
            name: $this->input('name'),
            type_id: $this->input('type'),
            brand_id: $this->input('brand'),
            model_id: $this->input('model'),
            mac_address: $this->input('mac'),
            ip_address: $this->input('ip'),
            username: $this->input('username'),
            secret: $this->input('secret'),
            node_id: $this->input('node'),
            comments: $this->input('comments') ?? null,
            status_id: $this->input('status'),
        );
    }
}
