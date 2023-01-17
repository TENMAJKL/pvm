<?php

use Majkel\Pisp\Lexer;
use Majkel\Pisp\Parser;
use Majkel\Pisp\Generator;

require __DIR__.'/vendor/autoload.php';

/**
 * Initial implementation of pips writen in php
 *
 * I hope I would be able to rewrite it in itself
 */

$name = $argv[1] ?? throw new Exception('file does not exist');

$lexer = new Lexer(file_get_contents($name));

$parser = new Parser($lexer->lex());

$generator = new Generator();

$result = $generator->generate($parser->parse());

file_put_contents(explode('.', $name)[0].'.pasm', $result);
