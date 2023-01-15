<?php

namespace Majkel\Pisp\AST;

class ProcedureArgsNode implements Node
{
    public readonly array $args;

    public function __construct(Node ...$args)
    {
        $this->args = $args;
    } 
}
