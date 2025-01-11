<?php

namespace Guedes\Challenges\Challenges;

class NumericalExpressions
{
    protected string $signature = 'ne';

    public function resolve()
    {

    }

    public function getSignature(): string
    {
        return $this->signature;
    }
}