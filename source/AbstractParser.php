<?php

namespace Pre\Standard;

use Closure;

use Pre\Standard\Exception\PropertyMissingException;
use function Pre\Standard\store;

use Yay\Ast;
use Yay\Parser;

abstract class AbstractParser
{
    public function __get($name)
    {
        if ($name === "onCommit") {
            return function (Ast $ast) {
                store($ast);
            };
        }

        throw new PropertyMissingException();
    }

    abstract public function parse(string $prefix = null): Parser;
}
