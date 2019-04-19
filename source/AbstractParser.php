<?php

namespace Pre\Standard;

use Closure;

use function Pre\Standard\Internal\store;

use Yay\Ast;
use Yay\Parser;

abstract class AbstractParser
{
    abstract public function parse(string $prefix = null): Parser;
}
