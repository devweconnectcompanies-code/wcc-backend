<?php

namespace App\Http\Requests\v1\Management\Services;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\DTOs\v1\management\services\ServiceDTO;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client' => 'required|integer|exists:clients,id',
            'code' => [
                'sometimes',
                'nullable',
                'string',
                'max:10',
                Rule::unique('services', 'code')
                    ->ignore($this->route('id'))
                    ->whereNotNull('code')
            ],
            'name' => [
                'sometimes',
                'nullable',
                'string',
                'max:10',
                Rule::unique('services', 'name')
                    ->ignore($this->route('id'))
                    ->whereNotNull('name')
            ],
            'node' => 'required|integer|exists:infrastructure_nodes,id',
            'equipment' => 'required|integer|exists:infrastructure_equipment,id',
            'installation_date' => 'required|date',
            'technician' => 'required|integer|exists:technicians,id',
            'lat' => 'required|numeric|between:-90,90',
            'long' => 'required|numeric|between:-180,180',
            'state' => 'required|integer|exists:config_states,id',
            'municipality' => 'required|integer|exists:config_municipalities,id',
            'district' => 'required|integer|exists:config_districts,id',
            'address' => 'required|string',
            'separation' => 'required|in:0,1',
            'status' => 'required|in:0,1',
            'comments' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'client.required' => 'No se ha ingresado ningún cliente.',
            'client.integer' => 'Formato de cliente incorrecto.',
            'client.exists' => 'El cliente seleccionado no existe.',
            'code.string' => 'Formato inválido para código.',
            'code.unique' => 'Este código ha sido registrado anteriormente.',
            'code.max' => 'El código debe contener como maximo 10 caracteres.',
            'name.string' => 'Formato de nombre incorrecto.',
            'name.unique' => 'Este nombre de servicio ya existe.',
            'name.max' => 'El nombre de servicio debe contener como maximo 10 caracteres.',
            'node.required' => 'Debes seleccionar un nodo.',
            'node.integer' => 'Formato de nodo incorrecto.',
            'node.exists' => 'El nodo seleccionado no existe.',
            'equipment.required' => 'Debes seleccionar un equipo.',
            'equipment.integer' => 'Formato de equipo incorrecto.',
            'equipment.exists' => 'El equipo seleccionado no existe.',
            'installation_date.required' => 'Debes ingresar una fecha de instalación.',
            'installation_date.date' => 'Formato de fecha incorrecto.',
            'technician.required' => 'Debes seleccionar un técnico.',
            'technician.integer' => 'Formato inválido.',
            'technician.exists' => 'El técnico seleccionado no existe.',
            'lat.required' => 'Latitud es un campo obligatorio.',
            'lat.numeric' => 'Formato incorrecto para latitud.',
            'lat.between' => 'Datos incorrectos para latitud.',
            'long.required' => 'Longitud es un campo obligatorio.',
            'long.numeric' => 'Formato incorrecto para longitud.',
            'long.between' => 'Datos incorrectos para longitud.',
            'state.required' => 'Debes seleccionar un Departamento.',
            'state.integer' => 'Formato inválido.',
            'state.exists' => 'El departamento seleccionado no existe.',
            'municipality.required' => 'Debes seleccionar un municipio.',
            'municipality.integer' => 'Formato inválido.',
            'municipality.exists' => 'El municipio seleccionado no existe.',
            'district.required' => 'Debes seleccionar un distrito.',
            'district.integer' => 'Formato inválido.',
            'district.exists' => 'El distrito seleccionado no existe.',
            'separation.required' => 'Factura independiente es un campo obligatorio.',
            'separation.in' => 'Formato inválido.',
            'status.required' => 'Debes seleccionar un estado.',
            'status.in' => 'Formato incorrecto.',
            'comments.string' => 'Caracteres no permitidos.',
        ];
    }

    public function toDTO(): ServiceDTO
    {
        return new ServiceDTO(
            client_id: $this->input('client'),
            code: $this->input('code') ?? null,
            name: $this->input('name') ?? null,
            node_id: $this->input('node'),
            equipment_id: $this->input('equipment'),
            installation_date: Carbon::parse($this->date('installation_date')),
            technician_id: $this->input('technician'),
            latitude: $this->input('lat'),
            longitude: $this->input('long'),
            state_id: $this->input('state'),
            municipality_id: $this->input('municipality'),
            district_id: $this->input('district'),
            address: $this->input('address'),
            separate_billing: $this->input('separation'),
            status_id: $this->input('status'),
            comments: $this->input('comments'),
        );
    }
}
