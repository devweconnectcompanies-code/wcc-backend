<?php

namespace App\Contexts\Accounting;

use App\Contracts\v1\Accounting\DTE\DTEGeneratorInterface;

class DTEContext
{
    private DTEGeneratorInterface $strategy;

    public function setStrategy(DTEGeneratorInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function execute(array $data): array
    {
        return $this->strategy->generate($data);
    }
}
