<?php

namespace Pre\Standard;

use Closure;

use Yay\Ast;
use Yay\Parser;

abstract class AbstractParser
{
    public function __invoke(...$params)
    {
        return $this->parse(...$params);
    }

    abstract public function parse(): Parser;
}
