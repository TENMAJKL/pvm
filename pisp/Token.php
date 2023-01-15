<?php

namespace Majkel\Pisp;

class Token
{
    public function __construct(
        public readonly TokenKind $kind,
        public readonly string $content,
        public readonly int $line
    ) {

    }
}
