<?php

namespace Majkel\Pisp\AST;

class NumberNode implements Node
{
    public function __construct(
        public readonly int $number
    ) {

    }
}
