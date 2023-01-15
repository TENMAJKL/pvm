<?php

namespace Majkel\Pisp\AST;

class NameNode implements Node
{
    public function __construct(
        public readonly string $name
    ) {

    }
}
