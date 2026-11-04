<?php

namespace App\Http\Requests\v1\Management\Supports;

use App\Enums\v1\Supports\SupportStatus;
use App\Enums\v1\Supports\SupportType;
use App\Models\Infrastructure\Network\EquipmentModel;
use App\Models\Services\ServiceModel;
use App\Models\Supports\SupportModel;
use App\Traits\Validation\Supports\EnumValidation;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateSupportRequest extends FormRequest
{
    use EnumValidation;

    public function authorize(): bool
    {
//        $supportId = $this->route('support') ?? $this->input('id');
        $supportId = $this->route('id') ?? $this->input('id');
        $support = SupportModel::query()->findOrFail($supportId);
        return $support !== null;
    }

    public function rules(): array
    {
        return [
            'id' => ['sometimes', 'integer', 'exists:supports,id'],

            //  Usando métodos del trait para reglas del enum
            'type' => $this->getOptionalSupportTypeRule(),
            'status' => $this->getOptionalSupportStatusRule(),

            'client' => ['sometimes', 'integer', 'exists:clients,id'],
            'branch' => ['sometimes', 'integer', 'exists:config_branches,id'],
            'state' => ['required', 'integer', 'exists:config_states,id'],
            'municipality' => ['required', 'integer', 'exists:config_municipalities,id'],
            'district' => ['required', 'integer', 'exists:config_districts,id'],
            'description' => ['required', 'string'],
            'address' => ['required', 'string'],
            'solution' => ['sometimes', 'string'],
            'comments' => ['sometimes', 'string'],

            'technician' => ['nullable', 'integer', 'exists:technicians,id'],
            'service' => ['nullable', 'integer', 'exists:services,id'],
            'profile' => ['nullable', 'integer', 'exists:management_internet_profiles,id'],
            'initial_date' => ['nullable', 'date'],
            'final_date' => ['nullable', 'date'],
            'node' => ['nullable', 'integer', 'exists:infrastructure_nodes,id'],
            'equipment' => ['nullable', 'integer', 'exists:infrastructure_equipment,id'],
        ];
    }

    public function withValidator($validator): void
    {
        if (!$this->supportExists()) {
            $validator->errors()->add('type', 'El soporte no existe.');
            return;
        }

        //  Obtener tipos efectivos (actuales o nuevos)
        $supportType = $this->getEffectiveSupportType();
        $supportStatus = $this->getEffectiveSupportStatus();

        if (!$supportType || !$supportStatus) return;


        //  Validaciones condicionales usando los métodos de enum
        if ($this->has('type') || $supportType->requiresContractDetails()) {
            $validator->sometimes(
                ['profile'],
                'required',
                fn() => $supportType->requiresContractDetails()
            );
        }

        if ($this->has('type') || $supportType->requiresService()) {
            $validator->sometimes(
                'service',
                'required',
                fn() => $supportType->requiresService()
            );
        }

        if ($this->requiresSolutionForClosure()) {
            $validator->sometimes(
                'solution',
                'required',
                fn() => true
            );
        }

        $validator->sometimes(
            'initial_date',
            'required',
            fn($input) => !empty($input->final_date)
        );

        $validator->sometimes(
            'node',
            'required',
            fn($input) => !empty($input->equipment)
        );

        //  Validaciones personalizadas
        $validator->after(function ($validator) use ($supportType) {
            $this->validateServiceBelongsToClient($validator);
            $this->validateEquipmentBelongsToNode($validator);

            //  Solo validar duplicados si se está cambiando el cliente o el tipo
            if ($this->has('client') || $this->has('type')) {
                $this->validateNoDuplicateSupport($validator, $supportType);
            }
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
                $validator->errors()->add('equipment', 'El equipo seleccionado no pertenece al nodo.');
            }
        }
    }

    //  Validación adicional para duplicados excluyendo el registro actual
    private function validateNoDuplicateSupport($validator, SupportType $supportType): void
    {
        if (!$this->has('client') || $this->has('type')) return;

        $supportId = $this->getSupportId();
        $clientId = (int)$this->input('client');

        //  No validar duplicados para tipos que lo permiten
        if ($supportType->allowsDuplicates()) return;

        //  Buscar soportes del mismo tipo y cliente excluyendo el actual
        $query = SupportModel::query()
            ->where([
                ['client_id', $clientId],
                ['type_id', $supportType->value]
            ])
            ->whereNull('closed_at')
            ->whereNotIn('status_id', SupportStatus::getClosedStatuses());

        //  Excluír el registro actual si existe
        if ($supportId) {
            $query->where('id', '!=', $supportId);
        }

        if ($query->exists()) {
            $validator->errors()->add('client', 'El cliente ya tiene un soporte pendiente de este tipo.');
        }
    }

    /**********
     * Obteniendo el id del soporte que se está editando
     **********/
    protected function getSupportId(): ?int
    {
        return $this->route('id') ?? $this->input('id');
    }

    //  Obteniendo soporte que se está editando
    protected function getCurrentSupport(): ?SupportModel
    {
        $supportId = $this->getSupportId();
        return $supportId ? SupportModel::query()->find($supportId) : null;
    }

    //  Verificando si el soporte existe
    protected function supportExists(): bool
    {
        return $this->getCurrentSupport() !== null;
    }

    //  Obtiene el tipo de soporte actual o el que se está enviando
    protected function getEffectiveSupportType(): ?SupportType
    {
        if ($this->has('type')) {
            return SupportType::tryFrom((int)$this->input('type'));
        }

        $currentSupport = $this->getCurrentSupport();
        return $currentSupport ? SupportType::tryFrom($currentSupport->type_id) : null;
    }

    //  Obtiene el estado del soporte actual o el que se le está enviando
    protected function getEffectiveSupportStatus(): ?SupportStatus
    {
        if ($this->has('status')) {
            return SupportStatus::tryFrom((int)$this->input('status'));
        }
        $currentSupport = $this->getCurrentSupport();
        return $currentSupport ? SupportStatus::tryFrom($currentSupport->status_id) : null;
    }

    //  Verifica si se está cambiando de un estado abierto a cerrado
    protected function isClosingSupport(): bool
    {
        if (!$this->has('status')) return false;

        $currentSupport = $this->getCurrentSupport();
        if (!$currentSupport) return false;

        $currentStatus = SupportStatus::tryFrom($currentSupport->status_id);
        $newStatus = SupportStatus::tryFrom((int)$this->input('status'));

        return $currentStatus && $newStatus && $currentStatus->isActive() && $newStatus->isClosed();
    }

    //  Verifica si se requiere una solución cuando se cierra
    protected function requiresSolutionForClosure(): bool
    {
        if (!$this->isClosingSupport()) {
            return false;
        }

        $newStatus = SupportStatus::tryFrom((int)$this->input('status'));
        return $newStatus && $newStatus->requiresSolution();
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Tipo de soporte es un campo obligatorio.',
            'type.integer' => 'Formato inválido.',
            'type.exists' => 'El tipo de soporte no existe.',
            'type.enum' => 'El tipo de soporte seleccionado no es válido.',

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
            'status.enum' => 'El estado seleccionado no es válido.',

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

            'initial_date.required' => 'Fecha inicial es requerida cuando hay fecha final.',
            'initial_date.date' => 'No es una fecha válida.',

            'final_date.date' => 'No es una fecha válida.',

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
            contract_date: $this->filled('initial_date') ? Carbon::parse($this->input('initial_date')) : null,
            contract_end_date: $this->filled('final_date') ? Carbon::parse($this->input('final_date')) : null,
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
