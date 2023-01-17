<?php

namespace Majkel\Pisp\AST;

class ProcedureDefinitionNode implements Node
{
    public readonly array $body;

    public function __construct(
        public readonly NameNode $name,
        public readonly ProcedureArgsNode $args,
        public readonly TypeNode $return_type,
        Node ...$body
    ) {
        $this->body = $body;
    }

    public function print(): array
    {
        $body = [];
        foreach ($this->body as $node) {
            foreach ($node->print() as $command) {
                $body[] = $command;
            }
        }

        return $body;
    }
}
