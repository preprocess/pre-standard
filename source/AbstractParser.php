<?php

namespace Pre\Standard;

use Closure;

use function Pre\Standard\Internal\store;

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
