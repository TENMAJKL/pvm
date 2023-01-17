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
        return ['psh '.$this->value->print()[0], 'get ', 'jmp']
    }
}
