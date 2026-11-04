<?php

namespace App\Traits\Validation\Supports;

use App\Enums\v1\Supports\SupportStatus;
use App\Enums\v1\Supports\SupportType;
use Illuminate\Validation\Rules\Enum;

trait EnumValidation
{
    protected function getSupportTypeRule(): array
    {
        return [
            'required',
            'integer',
            new Enum(SupportType::class),
            'exists:supports_types,id',
        ];
    }

    protected function getSupportStatusRule(): array
    {
        return [
            'required',
            'integer',
            new Enum(SupportStatus::class),
            'exists:supports_status,id',
        ];
    }

    protected function getOptionalSupportTypeRule(): array
    {
        return [
            'sometimes',
            'integer',
            'exists:supports_types,id',
            new Enum(SupportType::class),
        ];
    }

    protected function getOptionalSupportStatusRule(): array
    {
        return [
            'sometimes',
            'integer',
            'exists:supports_status,id',
            new Enum(SupportStatus::class),
        ];
    }

    protected function getSupportTypeFromInput(): ?SupportType
    {
        $value = $this->input('type');
        return $value ? SupportType::tryFrom((int)$value) : null;
    }

    protected function getSupportStatusFromInput(): ?SupportStatus
    {
        $value = $this->input('status');
        return $value ? SupportStatus::tryFrom((int)$value) : null;
    }
}
