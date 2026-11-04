<?php

namespace App\Http\Controllers\v1\management\clients;

use App\Http\Controllers\Controller;
use App\Models\Configuration\BranchModel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Clients\ContractRequest;
use App\Http\Resources\v1\management\clients\ContractResource;
use App\Models\Clients\ContractModel;
use App\Services\v1\management\client\ContractService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ContractsController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        return response()->json([
            'response' => ContractModel::query()
                ->with('contract_status')
                ->where('client_id', $request->input('clientID'))
                ->get()
        ]);
    }

    public function store(ContractRequest $request, ContractService $service): JsonResponse
    {
        $contract = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$contract,
            'contract' => new ContractResource($contract)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'contract' => ContractModel::query()->find($request->input('id')),
        ]);
    }

    public function update(ContractRequest $request, ContractModel $id, ContractService $service): JsonResponse
    {
        $contract = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$contract,
            'contract' => new ContractResource($contract)
        ]);
    }

    public function print(int $id)/*: Response*/
    {
        $query = ContractModel::query()
            ->with([
                'support.type',
                'support.details',
                'client.branch.state:id,name',
                'client.branch.municipality:id,name',
                'client.branch.district:id,name',
                'client.dui',
                'client.nit',
                'client.passport',
                'client.residence',
                'client.mobile',
                'support.state:id,name',
                'support.municipality:id,name',
                'support.district:id,name',
            ])
            ->find($id);

        $clientAddress = $query->support?->address . ', ';
        $clientAddress .= $query->support?->district?->name . ', ';
        $clientAddress .= $query->support?->municipality?->name . ', ';
        $clientAddress .= $query->support?->state?->name;

        $branchAddress = $query->client?->branch?->address . ', ';
        $branchAddress .= $query->client?->branch?->district?->name . ', ';
        $branchAddress .= $query->client?->branch?->municipality?->name . ', ';
        $branchAddress .= $query->client?->branch?->state?->name;

        $data = [
            'name' => "{$query->client?->name} {$query->client?->surname}",
            'document_type' => $query->client?->primary_document ? "{$query->client?->primary_document->type}" : "",
            'document_number' => $query->client?->primary_document ? "{$query->client?->primary_document->number}" : "",
            'phone' => $query->client?->mobile?->number,
            'address' => $clientAddress,
            'contract_date' => Carbon::parse($query->contract_date)->isoFormat('D [de] MMMM [del] YYYY'),
            'office_address' => $branchAddress,
            'plan' => $query->support?->details?->profile?->name,
            'price' => number_format($query->support?->details?->profile?->price, 2),
            'installation_price' => number_format($query->support?->type?->price, 2),
        ];

//        return $data;
        $pdf = Pdf::loadView('v1.management.pdf.clients.residential_contract', ['data' => $data])
            ->setPaper('A4', 'portrait');
        return $pdf->stream();
    }
}
