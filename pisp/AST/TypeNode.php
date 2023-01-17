<?php

namespace Majkel\Pisp\AST;

class TypeNode implements Node
{
    public const Types = [
        'int'
    ];

    public function __construct(
        public readonly string $type
    ) {

    }

    public function print(): array
    {
        return [];
    }
}
