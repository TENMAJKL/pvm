<?php

namespace Majkel\Pisp;

enum TokenKind
{
    case Open;
    case Close;
    case String;
    case Char;
    case Number;
    case Symbol;
    case Comment;
    case Comma;

    public static function fromRe(string $re): self
    {
        return match($re) {
           'Open' => self::Open,
           'Close' => self::Close,
           'String' => self::String,
           'Char' => self::Char,
           'Number' => self::Number,
           'Comment' => self::Comment,
           'Symbol' => self::Symbol,
           'Comma' => self::Comma,
        };
    }
}
