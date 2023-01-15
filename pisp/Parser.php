<?php

namespace Majkel\Pisp;

use CompileError;
use Generator;
use Majkel\Pisp\AST\NameNode;
use Majkel\Pisp\AST\Node;
use Majkel\Pisp\AST\NumberNode;
use Majkel\Pisp\AST\ProcedureDefinitionNode;
use Majkel\Pisp\AST\ProcedureArgsNode;
use Majkel\Pisp\AST\TypeNode;

class Parser
{


    public function __construct(
        private Stream $tokens
    ) {
    }

    public function parse(): array
    { 
        $tokens = [];
        while ($this->tokens->peek()) {
            $tokens[] = $this->parseToken();
        }
        return $tokens;
    }

    public function parseToken(): Node
    {
        return 
            $this->parseNumber()
            ?? $this->parseSymbol()
            ?? throw new CompileError('Unexpected token')
        ;
    }

    public function parseNumber(): ?Node
    {
        if ($this->tokens->current()->kind !== TokenKind::Number) {
            return null;
        }

        return new NumberNode((int)$this->tokens->current()->content); 
    }

    public function parseSymbol(): ?Node 
    {
        if ($this->tokens->current()->kind !== TokenKind::Symbol) {
            return null;
        }

        $name = $this->tokens->current()->content;

        if ($this->tokens->peek()->kind !== TokenKind::Open) {
            $symbol = $this->tokens->current()->content;
            if (in_array($symbol, TypeNode::Types)) {
                return new TypeNode($symbol);
            }
            return new NameNode($symbol);
        }

        $this->tokens->move();

        $children = [];

        do {
            $this->tokens->move();
            $children[] = $this->parseToken();
        } while ($this->tokens->move()->kind == TokenKind::Comma);

        if ($this->tokens->current()->kind !== TokenKind::Close) {
            throw new CompileError('Expected )');
        }

        return new (match ($name) {
            'proc', 'procedure' => ProcedureDefinitionNode::class,
            'args' => ProcedureArgsNode::class,
            default => throw new CompileError('Undefined symbol')
        })(...$children);
    }
}
