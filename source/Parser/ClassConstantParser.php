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

class ClassConstantParser extends AbstractParser
{
    public function parse(string $prefix = null): Parser
    {
        return chain(
            optional((new VisibilityModifiersParser())->parse(named("classConstant", $prefix))),
            buffer("const"),
            token(T_STRING)->as(named("classConstantName", $prefix)),
            buffer("="),
            expression()->as(named("classConstantValue", $prefix)),
            optional(buffer(";"))
        )
            ->as(named("classConstant", $prefix))
            ->onCommit($this->onCommit);
    }
}
