<?php

namespace App\Services\v1\management\configuration\clients;

use App\DTOs\v1\management\configuration\clients\DocumentTypeDTO;
use App\Models\Configuration\Clients\DocumentTypeModel;

class DocumentTypeService
{
    public function createDocument(DocumentTypeDTO $documentData): DocumentTypeModel
    {
        return DocumentTypeModel::create((array)$documentData);
    }

    public function updateDocument(DocumentTypeModel $documentTypeModel, DocumentTypeDTO $documentData): DocumentTypeModel
    {
        $documentTypeModel->fill((array)$documentData)->save();
        return $documentTypeModel;
    }
}
