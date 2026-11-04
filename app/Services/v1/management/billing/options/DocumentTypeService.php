<?php

namespace App\Services\v1\management\billing\options;

use App\DTOs\v1\management\billing\options\DocumentTypeDTO;
use App\Models\Billing\Options\DocumentTypeModel;

class DocumentTypeService
{
    public function createDocument(DocumentTypeDTO $documentData): DocumentTypeModel
    {
        return DocumentTypeModel::create($documentData->toArray());
    }

    public function updateDocument(DocumentTypeModel $documentTypeModel, DocumentTypeDTO $documentData): DocumentTypeModel
    {
        $documentTypeModel->update($documentData->toArray());
        return $documentTypeModel;
    }
}
