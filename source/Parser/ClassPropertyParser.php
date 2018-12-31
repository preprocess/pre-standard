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

class ClassPropertyParser extends AbstractParser
{
    public function parse(string $prefix = null): Parser
    {
        return chain(
            (new VisibilityModifiersParser())->parse(named("classProperty", $prefix)),
            optional((new NullableTypeParser())->parse(named("classProperty", $prefix))),
            token(T_VARIABLE)->as(named("classPropertyName", $prefix)),
            optional(buffer("=")),
            optional(expression())->as(named("classPropertyValue", $prefix)),
            optional(buffer(";"))
        )
            ->as(named("classProperty", $prefix))
            ->onCommit($this->onCommit);
    }
}
