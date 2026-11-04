<?php

namespace App\Strategies\v1\Supports\Tickets;

use App\Contracts\v1\Supports\SupportTicketInterface;
use App\Models\Supports\SupportModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class GenericTicket implements SupportTicketInterface
{

    public function render(SupportModel $support): string
    {
        $pdf = Pdf::loadView('v1.management.pdf.supports.generic', [
            'data' => [
                'type' => $support->type?->name,
                'ticket' => $support->ticket_number,
                'client' => "{$support->client?->name} {$support->client?->surname}",
                'node' => $support->service?->node?->name,
                'equipment' => $support->service?->equipment?->name,
                'latitude' => $support->service?->latitude,
                'longitude' => $support->service?->longitude,
                'state' => $support->state?->name,
                'district' => $support->district?->name,
                'address' => $support->address,
                'description' => $support->description,
                'technician' => $support->technician?->user?->name,
                'mobile' => $support->client?->mobile?->number,
                'year' => Carbon::today()->format('Y'),
                'solution' => $support->solution,
            ],
        ])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
