<?php

namespace App\Services\v1\management\client;

use App\DTOs\v1\management\client\EmailDTO;
use App\Models\Clients\EmailModel;

class EmailService
{
    public function createEmail(EmailDTO $emailDTO): EmailModel
    {
        return EmailModel::create($emailDTO->toArray());
    }

    public function updateEmail(EmailModel $emailModel, EmailDTO $emailDTO): EmailModel
    {
        $emailModel->update($emailDTO->toArray());
        return $emailModel;
    }
}
