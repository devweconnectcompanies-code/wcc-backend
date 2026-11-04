<?php

namespace App\Services\v1\management\client;

use App\DTOs\v1\management\client\DocumentDTO;
use App\Models\Clients\DocumentModel;

class DocumentService
{
    public function createDocument(DocumentDTO $documentDTO): DocumentModel
    {
        return DocumentModel::create($documentDTO->toArray());
    }

    public function updateDocument(DocumentModel $documentModel, DocumentDTO $documentDTO): DocumentModel
    {
        $documentModel->update($documentDTO->toArray());
        return $documentModel;
    }
}
