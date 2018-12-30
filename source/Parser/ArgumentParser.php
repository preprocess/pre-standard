<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;
use function Pre\Standard\named;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\expression;
use function Yay\optional;
use function Yay\token;

class ArgumentParser extends AbstractParser
{
    public function parse(string $prefix = null): Parser
    {
        return chain(
            optional(buffer("?"))->as(named("argumentNullable", $prefix)),
            optional((new TypeParser())->parse(named("argument", $prefix))),
            token(T_VARIABLE)->as(named("argumentName", $prefix)),
            optional(buffer("=")),
            optional(buffer("new"))->as(named("argumentNew", $prefix)),
            optional(expression())->as(named("argumentValue", $prefix))
        )
            ->as(named("argument", $prefix))
            ->onCommit($this->onCommit);
    }
}
