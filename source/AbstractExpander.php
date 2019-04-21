<?php

namespace Pre\Standard;

use Yay\Ast;
use Yay\Engine;
use Yay\Token;
use Yay\TokenStream;

abstract class AbstractExpander
{
    protected function find($source, $find)
    {
        if ($source instanceof Ast) {
            if ($source->label() === $find) {
                return $source->unwrap();
            }

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

    abstract public function expand(Ast $ast, Engine $engine, string $prefix = null);
}
