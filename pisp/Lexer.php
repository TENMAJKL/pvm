<?php

namespace Majkel\Pisp;

use Generator;

class Lexer
{
    public const Regex = '~(?<Open>\()
        |(?<Close>\))
        |(?<String>".+?")
        |(?<Char>\'.+?\')
        |(?<Number>\d)
        |(?<Comment>;;.+?\n|;-.+?-;)
        |(?<Line>\n)
        |(?<Symbol>[a-zA-Z0-9\+\-\*/\<\>=\$\#\@]+)
        |(?<White> )
        ~xsA';

    public function __construct(
        private string $code
    )  {

    }

    public function lex(): Stream
    {
        preg_match_all(self::Regex, $this->code, $matches, PREG_UNMATCHED_AS_NULL | PREG_SET_ORDER);
        $line = 1;
        $tokens = [];
        foreach ($matches as $match) {
            $token = array_filter($match, fn ($item) => null !== $item);
            $keys = array_keys($token);

            if ('Line' === $keys[1]) {
                ++$line;
                continue;
            }

            if ('White' === $keys[1]) {
                continue;
            }

            $tokens[] = new Token(TokenKind::fromRe($keys[1]), $token[0], $line);
        }

        return new Stream($tokens);
    }
}
