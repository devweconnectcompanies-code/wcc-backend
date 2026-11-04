<?php

namespace App\Http\Requests\v1\Management\Infrastructure\Equipments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\DTOs\v1\management\infrastructure\equipments\InventoryDTO;
use Carbon\Carbon;

class InventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand' => ['required', 'integer', 'exists:infrastructure_equipment_brands,id'],
            'type' => ['required', 'integer', 'exists:infrastructure_equipment_types,id'],
            'model' => ['required', 'integer', 'exists:infrastructure_equipment_models,id'],
            'service' => ['nullable', 'integer', 'exists:services,id'],
            'branch' => ['required', 'integer', 'exists:config_branches,id'],
            'mac' => [
                'required_if:_method,PUT',
                'mac_address',
                Rule::unique('infrastructure_residential_equipment_inventory', 'mac_address')
                    ->ignore($this->route('id')),
            ],
            'serial' => [
                'required_if:_method,PUT',
                'string',
                Rule::unique('infrastructure_residential_equipment_inventory', 'serial_number')
                    ->ignore($this->route('id')),
            ],
            'installation' => ['nullable', 'date'],
            'technician' => ['integer', 'exists:technicians,id'],
            'status' => ['required', 'integer', 'exists:config_infrastructure_equipment_status,id'],
            'comments' => ['nullable', 'string', 'between:5,500'],
            'company' => ['required', 'integer'],
            'file' => ['required_if:_method,POST', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'brand.required' => 'Marca es un campo obligatorio.',
            'brand.integer' => 'Formato incorrecto.',
            'brand.exists' => 'La marca seleccionada no existe.',

            'type.required' => 'Tipo es un campo obligatorio.',
            'type.integer' => 'Formato incorrecto.',
            'type.exists' => 'El tipo seleccionada no existe.',

            'model.required' => 'Modelo es un campo obligatorio.',
            'model.integer' => 'Formato incorrecto.',
            'model.exists' => 'El modelo seleccionado no existe.',

            'service.integer' => 'Formato incorrecto.',
            'service.exists' => 'El servicio seleccionado no existe.',

            'branch.required' => 'Sucursal es un campo obligatorio.',
            'branch.integer' => 'Formato incorrecto.',
            'branch.exists' => 'La sucursal seleccionada no existe.',

            'mac.required' => 'Dirección Mac es un campo obligatorio.',
            'mac.integer' => 'Formato incorrecto.',
            'mac.unique' => 'Esta Mac ya sido registrada.',

            'serial.required' => 'Serial es un campo obligatorio.',
            'serial.string' => 'Formato incorrecto.',
            'serial.unique' => 'Este Serial ya sido registrado.',

            'installation.date' => 'Fecha de instalación inválida.',

            'technician.integer' => 'Formato incorrecto.',
            'technician.exists' => 'El tecnico seleccionado no existe.',

            'status.required' => 'Estado es un campo obligatorio.',
            'status.integer' => 'Formato incorrecto.',
            'status.exists' => 'El estado seleccionado no existe.',

            'comments.string' => 'Formato incorrecto.',
            'comments.between' => 'El comentario debe contener entre 5 y 500 caracteres.',

            'file.required_if' => 'Archivo es un campo obligatorio.',
            'file.file' => 'Formato incorrecto.',
            'file.mimes' => 'El archivo debe ser un archivo de tipo: xls, xlsx, csv.',
            'file.max' => 'El archivo no debe superar los 10MB.',

            'company.required' => 'Empresa es un campo obligatorio.',
            'company.integer' => 'Formato incorrecto.',
        ];
    }

    public function toDTO(): InventoryDTO
    {
        return new InventoryDTO(
            brand_id: $this->input('brand'),
            type_id: $this->input('type'),
            model_id: $this->input('model'),
            branch_id: $this->input('branch') ?? 1,
            mac_address: $this->input('mac'),
            serial_number: $this->input('serial'),
            registration_date: $this->isMethod('POST') ? Carbon::today() : null,
            status_id: $this->input('status'),
            comments: $this->input('comments') ?? null,
            company_id: $this->input('company'),
        );
    }
}
