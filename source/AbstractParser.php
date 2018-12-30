<?php

namespace Pre\Standard;

use Closure;

use Pre\Standard\Exception\PropertyMissingException;
use function Pre\Standard\store;

use Yay\Ast;
use Yay\Parser;

abstract class AbstractParser
{
    protected $onCommit;

    public function __construct()
    {
        $this->onCommit = function (Ast $ast) {
            store($ast);
        };
    }

    abstract public function parse(string $prefix = null): Parser;
}
