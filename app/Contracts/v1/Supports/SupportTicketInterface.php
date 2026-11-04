<?php

namespace App\Contracts\v1\Supports;

use App\Models\Supports\SupportModel;

interface SupportTicketInterface
{
    public function render(SupportModel $support): string;
}
