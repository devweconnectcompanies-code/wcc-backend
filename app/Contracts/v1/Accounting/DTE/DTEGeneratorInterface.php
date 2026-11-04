<?php

namespace App\Contracts\v1\Accounting\DTE;

interface DTEGeneratorInterface
{
    public function generate(array $data): array;
}
