<?php

namespace App\DTOs\v1\management\billing\options;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;

class ActivityDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,
        #[Required, StringType]
        public readonly string $code,
        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {

    }

    public static function fromArray(array $data): self
    {
        return self::from($data);
    }

    public function toArray(): array
    {
        return $this->all();
    }
}
