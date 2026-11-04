<?php

namespace App\Http\Controllers\v1\management\configuration\clients;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\management\configuration\clients\documentResource;
use App\Services\v1\management\configuration\clients\DocumentTypeService;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Configuration\DocumentTypeRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Configuration\Clients\DocumentTypeModel;

class DocumentTypesController extends Controller
{
    public function dataViewer(Request $request): JsonResponse
    {
        $query = DocumentTypeModel::query();

        if ($request->has('e')) {
            foreach ($request->e as $filter) {
                if (!isset($filter['column'], $filter['data'])) continue;
                $data = json_decode($filter['data'], true);
                if (!is_array($data)) continue;
                match ($filter['column']) {
                    'status' => $query->whereIn('status_id', $data),
                    default => null
                };
            }
        }
        $query = $query->orderByDesc('status_id')->advancedFilter();

        return response()->json(['collection' => $query]);
    }

    public function store(DocumentTypeRequest $request, DocumentTypeService $documentTypeService): JsonResponse
    {
        $document = $documentTypeService->createDocument($request->toDTO());

        return response()->json([
            'saved' => (bool)$document,
            'document' => new DocumentResource($document),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['document' => DocumentTypeModel::query()->findOrFail($request->id)]);
    }

    public function update(DocumentTypeRequest $request, DocumentTypeModel $id, DocumentTypeService $documentTypeService): JsonResponse
    {
        $document = $documentTypeService->updateDocument($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$document,
            'document' => new DocumentResource($document),
        ]);
    }
}
