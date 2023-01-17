<?php

namespace Majkel\Pisp;

class Generator
{
    public function generate(array $nodes): string
    {
        $lines = [];
        $commands = 0;
        $main = 0;
        $procedures = [];
        foreach ($nodes as $node) {
            foreach ($node->print() as $line) {
                if ($node->name->name === 'entry') {
                    $main = $commands; 
                }
                
                $procedures[$node->name->name] = $commands;

                $commands++;
                $lines[] = str_replace(['%current', '%'], [], $line);
            }
        }

        $lines = ['psh '.$commands + 2, 'jmp '.$main, ...$lines, 'ext'];

        return implode("\n", $lines);
    }
}
