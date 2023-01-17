<?php

namespace Majkel\Pisp\AST;

class UserProcCall implements Node
{
    private array $args;

    public function __construct(
        private string $proc,
        Node ...$args
    ) {
        $this->args = $args;
    }

    public function print(): array
    {
        $args = [];
        return ['psh %current', ...$args, 'jmp %proc_'.$this->proc];
    }
}
