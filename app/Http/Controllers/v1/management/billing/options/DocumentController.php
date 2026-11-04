<?php

namespace App\Http\Controllers\v1\management\billing\options;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Billing\Options\DocumentTypeRequest;
use App\Services\v1\management\billing\options\DocumentTypeService;
use App\Http\Resources\v1\management\billing\options\DocumentTypeResource;
use App\Models\Billing\Options\DocumentTypeModel;
use App\Services\v1\management\DataViewerService;

class DocumentController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewerService): JsonResponse
    {
        $query = DocumentTypeModel::query();

        return $dataViewerService->handle($request, $query, [
            'status' => fn($q, $data) => $query->whereIn('status_id', $data),
        ]);
    }

    public function store(DocumentTypeRequest $request, DocumentTypeService $documentTypeService): JsonResponse
    {
        $document = $documentTypeService->createDocument($request->toDTO());

        return response()->json([
            'saved' => (bool)$document,
            'document' => new DocumentTypeResource($document),
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
            'document' => new DocumentTypeResource($document),
        ]);
    }
}
