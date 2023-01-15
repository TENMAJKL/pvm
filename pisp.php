<?php

use Majkel\Pisp\Lexer;
use Majkel\Pisp\Parser;

require __DIR__.'/vendor/autoload.php';

/**
 * Initial implementation of pips writen in php
 *
 * I hope I would be able to rewrite it in itself
 */

$name = $argv[1] ?? throw new Exception('file does not exist');

$lexer = new Lexer(file_get_contents($name));

$parser = new Parser($lexer->lex());

print_r($parser->parse());
