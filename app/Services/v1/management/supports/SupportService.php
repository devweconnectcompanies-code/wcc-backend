<?php

namespace App\Services\v1\management\supports;

use App\DTOs\v1\management\supports\SupportDTO;
use App\Enums\v1\Supports\SupportStatus;
use App\Enums\v1\Supports\SupportType;
use App\Models\Supports\LogModel;
use App\Models\Supports\SupportModel;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class SupportService
{
    public function create(SupportDTO $DTO): SupportModel
    {
        $type = SupportType::from((int)$DTO->type_id);

        //  Si el tipo de soportes no permite duplicados, validar
        if (!$type->allowsDuplicates()) {
            $exists = SupportModel::query()
                ->where('client_id', $DTO->client_id)
                ->whereIn('status_id', SupportStatus::getActiveStatuses())
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'client' => 'Este cliente ya tiene un soporte activo.'
                ]);
            }
        }

        $ticket = $this->createTicketNumber($DTO->type_id);

        //  Buscando estrategia correcta
        $strategy = SupportFactory::make((int)$DTO->type_id);
        return $strategy->handle($DTO->toArray(), $ticket);
    }

    public function read(int $id): SupportModel
    {
        return SupportModel::query()
            ->with(['client', 'service', 'details', 'contract'])
            ->findOrFail($id);
    }

    public function update(SupportModel $model, SupportDTO $DTO)/*: SupportModel*/
    {
        $strategy = SupportFactory::make((int)$DTO->type_id);
        $support = $strategy->update($model, $DTO->toArray());

        //  Si el soporte se está cerrando
        if ((int)$DTO->status_id === SupportStatus::ENDED->value) {
            $closedAt = Carbon::now();
            $support->closed_at = $closedAt;
            $support->solution = $DTO->solution ?? $support->solution;
            $support->comments = $DTO->comment ?? $support->comments;

            //  Calculando tiempo de resolución
            if ($support->creation_date) {
                $dueDate = Carbon::parse($support->due_date);
                $closedAt = Carbon::parse($support->close_date);
                $support->resolution_time = $closedAt->diffInMicroseconds($dueDate);

                //  Verificando SLA
                if ($support->due_date && $closedAt->greaterThan($dueDate)) {
                    $support->breached_sla = true;
                } else {
                    $support->breached_sla = false;
                }
            }
            $support->save();
        }
        return $support;
    }

    public function printTicket(SupportModel $support): string
    {
        $strategy = TicketFactory::make((int)$support->type_id);
        return $strategy->render($support);
    }

    public function getLogs(int $id): array
    {
        $query = LogModel::query()
            ->with(['user:id,name'])
            ->where('support_id', $id)
            ->get();

        return [
            'ticket' => $query->first()->support?->ticket_number,
            'collection' => $query,
        ];
    }

    private function createTicket(): string
    {
        $prefix = 'NETPLUS_SPT-';
        $total = SupportModel::query()->withTrashed()->count();
        $totalLength = 10;
        $zeroFill = max(0, $totalLength - strlen($total));
        $filling = str_repeat('0', $zeroFill);
        $prefix .= $filling . ($total + 1);
        return $prefix;
    }

    private function createTicketNumber(int $type): string
    {
        $totalLength = 5;
        $totalSupports = SupportModel::query()
            ->where('type_id', $type)
            ->whereBetween('creation_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->withTrashed()
            ->count();
        $zeroFill = max(0, $totalLength - strlen($totalSupports));
        $filling = str_repeat('0', $zeroFill);
        $prefix = match ($type) {
            1 => 'NETPLUS-INI-',
            2 => 'NETPLUS-INE-',
            3 => 'NETPLUS-SPI-',
            4 => 'NETPLUS-SPE-',
            5 => 'NETPLUS-CDO-',
            6 => 'NETPLUS-RNI-',
            7 => 'NETPLUS-RNE-',
            8 => 'NETPLUS-DES-',
            9 => 'NETPLUS-VEQ-',
        };
        $prefix .= Carbon::today()->format('Y') . '-';
        $prefix .= $filling . ($totalSupports + 1);
        return $prefix;
    }
}
