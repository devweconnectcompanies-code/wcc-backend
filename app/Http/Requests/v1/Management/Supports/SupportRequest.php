<?php

namespace App\Http\Requests\v1\Management\Supports;

use App\DTOs\v1\management\supports\SupportDTO;
use App\Enums\v1\Supports\SupportStatus;
use App\Enums\v1\Supports\SupportType;
use App\Models\Infrastructure\Network\EquipmentModel;
use App\Models\Services\ServiceModel;
use App\Models\Supports\SupportModel;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //  Campos requeridos en todos los soportes
            'type' => [
                'required',
                'integer',
                'exists:supports_types,id',
                Rule::enum(SupportType::class),
            ],
            'client' => ['required', 'integer', 'exists:clients,id',],
            'branch' => ['required', 'integer', 'exists:config_branches,id'],
            'state' => ['required', 'integer', 'exists:config_states,id'],
            'municipality' => ['required', 'integer', 'exists:config_municipalities,id'],
            'district' => ['required', 'integer', 'exists:config_districts,id'],
            'status' => [
                'required',
                'integer',
                'exists:supports_status,id',
                Rule::enum(SupportStatus::class),
            ],
            'description' => ['required', 'string'],
            'address' => ['required', 'string'],

            //  Campos condicionales según estado de soporte
            'technician' => ['nullable', 'integer', 'exists:technicians,id'],

            //  Campos condicionales según tipo de soporte
            'service' => ['nullable', 'integer', 'exists:services,id'],
            'solution' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            'profile' => ['nullable', 'integer', 'exists:management_internet_profiles,id'],
            'initial_date' => ['nullable', 'date'],
            'final_date' => ['nullable', 'date'],
            'node' => ['nullable', 'integer', 'exists:infrastructure_nodes,id'],
            'equipment' => ['nullable', 'integer', 'exists:infrastructure_equipment,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $supportType = SupportType::tryFrom((int)$this->input('type'));
        $supportStatus = SupportStatus::tryFrom((int)$this->input('status'));

        if (!$supportType || !$supportStatus) return;

        //  Validación condicional para campos requeridos según tipo de soporte
        $validator->sometimes(
            ['profile'],
            'required',
            function ($input) use ($supportType) {
                return $supportType->requiresContractDetails();
            }
        );

        //  Validación condicional para servicio según tipo de soporte
        $validator->sometimes(
            ['service'],
            'required',
            function ($input) use ($supportType) {
                return $supportType->requiresService();
            }
        );

        //  Validación condicional para técnico, según estado de soporte
        $validator->sometimes(
            'technician',
            'required',
            function ($input) use ($supportStatus) {
                return $supportStatus->requiresTechnicians();
            }
        );

        //  Validación condicional para solución según estado
        $validator->sometimes(
            'solution',
            'required',
            function ($input) use ($supportStatus) {
                return $supportStatus->requiresSolution();
            }
        );

        //  Validación adicional: si hay fecha final, debe haber fecha inicial
        $validator->sometimes(
            'initial_date',
            'required',
            function ($input) {
                return !empty($input->final_date);
            }
        );

        //  Validación adicional: si hay equipo debe haber nodo
        $validator->sometimes(
            'node',
            'required',
            function ($input) {
                return !empty($input->equipment);
            }
        );

        /*******************************
         *  Validaciones personalizadas usando enums
         ******************************/
        $validator->after(function ($validator) use ($supportType) {
            $this->validateServiceBelongsToClient($validator);
            $this->validateEquipmentBelongsToNode($validator);
//            $this->validateClientHasNoPendingInstallation($validator, $supportType);
//            $this->validateClientHasNoPendingSupport($validator, $supportType);
        });
    }

    private function validateServiceBelongsToClient($validator): void
    {
        if ($this->filled(['client', 'service'])) {
            $serviceExists = ServiceModel::query()
                ->where([
                    ['id', $this->input('service')],
                    ['client_id', $this->input('client')],
                ])
                ->exists();

            if (!$serviceExists) {
                $validator->errors()->add('service', 'El servicio seleccionado no pertenece al cliente.');
            }
        }
    }

    private function validateEquipmentBelongsToNode($validator): void
    {
        if ($this->filled(['node', 'equipment'])) {
            $equipmentExists = EquipmentModel::query()
                ->where([
                    ['id', $this->input('equipment')],
                    ['node_id', $this->input('node')],
                ])
                ->exists();

            if (!$equipmentExists) {
                $validator->errors()->add('equipment', 'El equipo seleccionado no pertenece nodo.');
            }
        }
    }

    private function validateClientHasNoPendingInstallation($validator, SupportType $supportType): void
    {
        // Usando el método del enum para verificar si es instalación
        if ($supportType->isInstallation()) {
            $clientId = $this->input('client');

            //  Usando método estático del enum para obtener tipos de instalación
            $exists = SupportModel::query()
                ->where('client_id', $clientId)
                ->whereIn('type_id', SupportType::getInstallationTypes())
                ->whereNull('closed_at')
                ->whereNotIn('status_id', SupportStatus::getClosedStatuses())
                ->exists();

            if ($exists) {
                $validator->errors()->add('client', 'Este cliente tiene una instalación en proceso');
            }
        }
    }

    private function validateClientHasNoPendingSupport($validator, SupportType $supportType): void
    {
        $clientId = $this->input('client');

        //  Verificando si el soporte permite duplicados
        if ($supportType->allowsDuplicates()) return;

        // Buscando cualquier soporte del cliente que no esté finalizado/cancelado/observado
        $exists = SupportModel::query()
            ->where([
                ['client_id', $clientId],
                ['type_id', $supportType->value],
            ])
            ->whereNull('closed_at')
            ->whereNotIn('status_id', SupportStatus::getClosedStatuses())
            ->exists();

        if ($exists) {
            $validator->errors()->add('client', 'Este cliente ya tiene un soporte pendiente.');
        }
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Tipo de soporte es un campo obligatorio.',
            'type.integer' => 'Formato inválido.',
            'type.exists' => 'El tipo de soporte no existe.',

            'client.required' => 'Cliente es un campo obligatorio.',
            'client.integer' => 'Formato inválido.',
            'client.exists' => 'El cliente seleccionado no existe.',

            'branch.required' => 'Sucursal es un campo obligatorio.',
            'branch.integer' => 'Formato inválido.',
            'branch.exists' => 'La sucursal seleccionada no existe.',

            'state.required' => 'Departamento es un campo obligatorio.',
            'state.integer' => 'Formato inválido.',
            'state.exists' => 'El departamento seleccionado no existe.',

            'municipality.required' => 'Municipio es un campo obligatorio.',
            'municipality.integer' => 'Formato inválido.',
            'municipality.exists' => 'El municipio seleccionado no existe.',

            'district.required' => 'Distrito es un campo obligatorio.',
            'district.integer' => 'Formato inválido.',
            'district.exists' => 'El distrito seleccionado no existe.',

            'status.required' => 'Estado de soporte es un campo obligatorio.',
            'status.integer' => 'Formato inválido.',
            'status.exists' => 'El estado seleccionado no existe.',

            'description.required' => 'Descripción es un campo obligatorio.',
            'description.string' => 'Solo se admiten letras.',

            'address.required' => 'Dirección es un campo obligatorio.',
            'address.string' => 'Solo se admiten letras.',

            'technician.required' => 'Técnico es un campo obligatorio.',
            'technician.integer' => 'Formato inválido',
            'technician.exists' => 'El técnico seleccionado no existe.',

            'service.required' => 'Servicio es un campo obligatorio.',
            'service.integer' => 'Formato inválido.',
            'service.exists' => 'El servicio seleccionado no existe.',

            'solution.required' => 'Solución es un campo obligatorio.',
            'solution.string' => 'Solo se admiten letras.',

            'comments.string' => 'Solo se admiten letras.',

            'profile.required' => 'Perfil es un campo obligatorio.',
            'profile.integer' => 'Formato inválido.',
            'profile.exists' => 'El perfil seleccionado no existe.',

            'initial_date.date' => 'No es una fecha.',

            'final_date.date' => 'No es una fecha.',

            'node.required' => 'Nodo es un campo obligatorio.',
            'node.integer' => 'Formato inválido.',
            'node.exists' => 'El nodo seleccionado no existe.',

            'equipment.required' => 'Equipo es un campo obligatorio.',
            'equipment.integer' => 'Formato inválido.',
            'equipment.exists' => 'El equipo seleccionado no existe.',
        ];
    }

    public function toDTO(): SupportDTO
    {
        return new SupportDTO(
            type_id: $this->input('type'),
            ticket_number: '',
            client_id: $this->input('client'),
            service_id: $this->input('service'),
            branch_id: $this->input('branch'),
            creation_date: Carbon::now(),
            due_date: null,
            description: $this->input('description'),
            technician_id: $this->input('technician') ?? null,
            state_id: $this->input('state'),
            municipality_id: $this->input('municipality'),
            district_id: $this->input('district'),
            address: $this->input('address'),
            closed_at: null,
            solution: $this->input('solution') ?? null,
            comments: $this->input('comments') ?? null,
            user_id: Auth::user()->id,
            status_id: $this->input('status'),
            breached_sla: false,
            resolution_time: null,
            internet_profile_id: $this->input('profile') ?? null,
            node_id: $this->input('node') ?? null,
            equipment_id: $this->input('equipment') ?? null,
            contract_date: Carbon::parse($this->input('initial_date')) ?? null,
            contract_end_date: Carbon::parse($this->input('final_date')) ?? null,
        );
    }

    //  Métodos helper para obtener instancias enum
    public function getSupportType(): ?SupportType
    {
        return SupportType::tryFrom((int)$this->input('type'));
    }

    public function getSupportStatus(): ?SupportStatus
    {
        return SupportStatus::tryFrom((int)$this->input('status'));
    }
}
