<?php

namespace App\Strategies\v1\Supports\Tickets;

use App\Contracts\v1\Supports\SupportTicketInterface;
use App\Models\Supports\SupportModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ContractTicket implements SupportTicketInterface
{

    public function render(SupportModel $support): string
    {
        $clientAddress = $support->address . ', ';
        $clientAddress .= $support->district?->name . ', ';
        $clientAddress .= $support->municipality?->name . ', ';
        $clientAddress .= $support->state?->name;

        $branchAddress = $support->branch?->address . ', ';
        $branchAddress .= $support->branch?->district?->name . ', ';
        $branchAddress .= $support->branch?->municipality?->name . ', ';
        $branchAddress .= $support->branch?->state?->name;

        $data = [
            'name' => "{$support->client?->name} {$support->client?->surname}",
            'document_type' => $support->client?->primary_document ? "{$support->client?->primary_document->type}" : '',
            'document_number' => $support->client?->primary_document ? "{$support->client?->primary_document->number}" : '',
            'phone' => $support->client?->mobile?->number ?? '',
            'address' => $clientAddress,
            'contract_date' => Carbon::parse($support->contract->contract_date)->isoFormat('D [de] MMMM [del] YYYY'),
            'office_address' => $branchAddress,
            'plan' => $support->details?->profile?->name,
            'price' => number_format($support->details?->profile?->price, 2),
            'installation_price' => $support->type?->price,
        ];

        $pdf = Pdf::loadView('v1.management.pdf.clients.residential_contract', [
            'data' => $data,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
