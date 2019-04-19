<?php

namespace Pre\Standard;

use Yay\Ast;
use Yay\Engine;
use Yay\Token;
use Yay\TokenStream;

abstract class AbstractExpander
{
    protected function resolve($source)
    {
        if ($source instanceof TokenStream) {
            $source = $source->getAst();
        }

        return $source;
    }

    protected function find($source, $find)
    {
        if ($source instanceof Ast) {
            $source = $source->unwrap();
        }

        foreach ($source as $key => $value) {
            if ($key === $find) {
                return $value;
            }

            if (is_array($value)) {
                if (!empty($result = $this->find($value, $find))) {
                    return $result;
                }
            }
        }

        return null;
    }

    abstract public function expand($source, Engine $engine, string $prefix = null): TokenStream;
}
