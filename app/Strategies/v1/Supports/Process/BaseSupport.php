<?php

namespace App\Strategies\v1\Supports\Process;

use App\Models\Supports\SupportModel;
use Carbon\Carbon;

class BaseSupport
{
    protected function createSupport(array $data, string $ticket, ?int $contract_id = null): SupportModel
    {
        $creationDate = Carbon::parse($data['creation_date']);

        $data = [
            'type_id' => $data['type_id'],
            'ticket_number' => $ticket,
            'client_id' => $data['client_id'],
            'contract_id' => $contract_id,
            'service_id' => $data['service_id'],
            'branch_id' => $data['branch_id'],
            'creation_date' => $creationDate->toDateTimeString(),
            'due_date' => $creationDate->addHours(72)->toDateTimeString(),
            'description' => $data['description'],
            'technician_id' => $data['technician_id'],
            'state_id' => $data['state_id'],
            'municipality_id' => $data['municipality_id'],
            'district_id' => $data['district_id'],
            'address' => $data['address'],
            'closed_at' => null,
            'solution' => $data['solution'],
            'comments' => $data['comments'],
            'user_id' => (int)$data['user_id'],
            'status_id' => (int)$data['status_id'],
            'breached_sla' => false,
            'resolution_time' => null,
        ];

        return SupportModel::query()->create($data);
    }

    protected function updateSupport(SupportModel $model, array $data): SupportModel
    {
        $payload = [
            'type_id' => $data['type_id'],
            'client_id' => $data['client_id'],
            'service_id' => $data['service_id'],
            'branch_id' => $data['branch_id'],
            'description' => $data['description'],
            'technician_id' => $data['technician_id'],
            'state_id' => $data['state_id'],
            'municipality_id' => $data['municipality_id'],
            'district_id' => $data['district_id'],
            'address' => $data['address'],
            'solution' => $data['solution'],
            'comments' => $data['comments'],
            'status_id' => (int)$data['status_id'],
        ];

        $model->update($payload);
        return $model->refresh();
    }
}
