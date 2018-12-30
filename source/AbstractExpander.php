<?php

namespace Pre\Standard;

use function Pre\Standard\match;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

abstract class AbstractExpander
{
    protected function resolve($source): array
    {
        // this will get an Ast from a TokenStream
        if ($source instanceof TokenStream) {
            $source = match($source);
        }

        // this will get a nested array from an Ast
        if ($source instanceof Ast) {
            $source = $source->unwrap();
        }

        return $source;
    }

    abstract public function expand($source, Engine $engine): TokenStream;
}
