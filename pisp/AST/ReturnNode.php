<?php

namespace Majkel\Pisp\AST;

class ReturnNode implements Node
{
    public function __construct(
        public readonly Node $value
    ) {
        
    }

    public function print(): array
    {
        if ($this->value instanceof NumberNode) {
            $psh = ['psh '.$this->value->print()[0]];
        } else {
            $psh = $this->value->print();
        }
        return [...$psh, 'swp', 'jmp'];
    }
}
