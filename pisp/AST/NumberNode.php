<?php

namespace Majkel\Pisp\AST;

class NumberNode implements Node
{
    public function __construct(
        public readonly int $number
    ) {

    }

    public function print(): array
    {
        return [(string) $this->number];
    }
}
