<?php

namespace Majkel\Pisp\AST;

use CompileError;

class ProcedureArgsNode implements Node
{
    public readonly array $args;

    public function __construct(Node ...$args)
    {
        $this->args = $args;
    } 

    public function print(): array
    {
        throw new CompileError('WHY EXACTLY THIS HAPPENED?');
    }
}
