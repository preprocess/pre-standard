<?php

namespace Pre\Standard;

use function Pre\Standard\matchAst;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

abstract class AbstractExpander
{
    abstract public function expand(TokenStream $stream, Engine $engine): TokenStream;
}
