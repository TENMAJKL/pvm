<?php

namespace Majkel\Pisp;

use CompileError;
use Majkel\Pisp\AST\NameNode;
use Majkel\Pisp\AST\Node;
use Majkel\Pisp\AST\NumberNode;
use Majkel\Pisp\AST\ReturnNode;
use Majkel\Pisp\AST\UserProcCall;
use Majkel\Pisp\AST\ProcedureDefinitionNode;
use Majkel\Pisp\AST\ProcedureArgsNode;
use Majkel\Pisp\AST\TypeNode;

class Parser
{
    private array $procedures;

    public function __construct(
        private Stream $tokens
    ) {
    }

    public function parse(): array
    { 
        $tokens = [];
        while ($this->tokens->current()) {
            $tokens[] = $this->parseToken();
            $this->tokens->move();
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

        $this->tokens->move(2);

        $children = [];

        while ($this->tokens->current()->kind !== TokenKind::Close) {
            $children[] = $this->parseToken();
            $this->tokens->move();
        }

        return match ($name) {
            'proc', 'procedure' => new ProcedureDefinitionNode(...$children),
            'args' => new ProcedureArgsNode(...$children),
            'return' => new ReturnNode(...$children),
            default => new UserProcCall($name, ...$children)
        };
    }
}
