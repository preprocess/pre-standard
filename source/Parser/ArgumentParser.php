<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;
use function Pre\Standard\Internal\named;

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
            optional((new NullableTypeParser())->parse(named("argument", $prefix))),
            token(T_VARIABLE)->as(named("argumentName", $prefix)),
            optional(
                chain(
                    buffer("="),
                    optional(buffer("new"))->as(named("argumentNew", $prefix)),
                    expression()->as(named("argumentValue", $prefix))
                )
            )->as(named("argumentAssignment", $prefix))
        )
            ->as(named("argument", $prefix))
            ->onCommit($this->onCommit);
    }
}
