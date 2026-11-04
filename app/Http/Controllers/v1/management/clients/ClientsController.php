<?php

namespace App\Http\Controllers\v1\management\clients;

use App\Http\Controllers\Controller;
use App\Services\v1\imports\ImportClientsService;
use App\Services\v1\management\client\ClientGeneralDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Clients\ClientRequest;
use App\Models\Clients\ClientModel;
use App\Services\v1\management\DataViewerService;
use App\Http\Resources\v1\management\clients\GeneralDataResource;

class ClientsController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewerService): JsonResponse
    {
        $query = ClientModel::query()->with([
            'branch',
            'client_type',
            'dui',
            'mobile',
            'address.state',
            'address.district'
        ]);

        return $dataViewerService->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
            'branch' => fn($q, $data) => $q->whereIn('branch_id', $data),
            'type' => fn($q, $data) => $q->whereIn('client_type_id', $data),
            'state' => fn($q, $data) => $q->whereHas('address', function ($q) use ($data) {
                return $q->whereIn('state_id', $data);
            }),
            'district' => fn($q, $data) => $q->whereHas('address', function ($q) use ($data) {
                return $q->whereIn('district_id', $data);
            }),
        ]);
    }

    public function store(ClientRequest $request, ClientGeneralDataService $clientGeneralDataService): JsonResponse
    {
        $client = $clientGeneralDataService->createClient($request->toDTO());
        return response()->json([
            'saved' => (bool)$client,
            'client' => new GeneralDataResource($client),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['client' => ClientModel::query()->findOrFail($request->id)]);
    }

    public function update(ClientRequest $request, ClientModel $id, ClientGeneralDataService $clientGeneralDataService): JsonResponse
    {
        $client = $clientGeneralDataService->updateClient($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$client,
            'client' => new GeneralDataResource($client),
        ]);
    }

    public function searchByName(Request $request): JsonResponse
    {
        $query = ClientModel::query()
            ->where('status_id', 1)
            ->where('name', 'ILIKE', '%' . $request->client . '%')
            ->orWhere('surname', 'ILIKE', '%' . $request->client . '%')
            ->get()
            ->makeHidden('status');
        $data = [];
        foreach ($query as $item) {
            $el = [
                'id' => $item->id,
                'name' => ucfirst("ID: {$item->id} - {$item->name} {$item->surname}"),
            ];
            $data[] = $el;
        }

        return response()->json(['clients' => $data]);
    }

    public function clientBranch(int $id, ClientGeneralDataService $service): JsonResponse
    {
        return response()->json([
            'branch' => new GeneralDataResource($service->getClientBranch($id))
        ]);
    }

    public function searchById(int $id): JsonResponse
    {
        $query = ClientModel::query()
            ->findOrFail($id);

        $data = [
            'id' => $query->id,
            'name' => ucwords("{$query->name} {$query->surname}"),
        ];
        return response()->json(['response' => [$data]]);
    }

    public function importClients(ImportClientsService $service): JsonResponse
    {
        $clients = $service->importClients();
        return response()->json([
            'clients' => new GeneralDataResource($clients)
        ]);
    }
}
