<?php

namespace App\Http\Controllers\v1\management\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Clients\DocumentRequest;
use App\Models\Clients\DocumentModel;
use App\Services\v1\management\client\DocumentService;
use App\Http\Resources\v1\management\clients\DocumentResource;

class DocumentsController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        return response()->json(['response' => DocumentModel::query()->with('document_type')->where('client_id', $request->clientID)->get()]);
    }

    public function store(DocumentRequest $request, DocumentService $documentService): JsonResponse
    {
        $document = $documentService->createDocument($request->toDTO());

        return response()->json([
            'saved' => (bool)$document,
            'document' => new DocumentResource($document)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'document' => DocumentModel::query()
                ->with('document_type')
                ->where([
                    ['id', $request->documentID],
                    ['client_id', $request->clientID],
                ])
                ->firstOrFail()
        ]);
    }

    public function update(DocumentRequest $request, DocumentModel $id, DocumentService $documentService): JsonResponse
    {
        $document = $documentService->updateDocument($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$document,
            'document' => new DocumentResource($document)
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dui' => 'required|regex:/^\d{8}-\d$/',
        ]);
        $document = DocumentModel::query()
            ->with(['client:id,name,surname,status_id'])
            ->where([
                ['status_id', 1],
                ['number', 'ILIKE', "%{$validated['dui']}%"],
            ])
            ->select('id', 'client_id', 'document_type_id', 'number', 'status_id')
            ->first();

        return response()->json(['response' => $document]);
    }
}
