<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;
use function Pre\Standard\Internal\named;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\expression;
use function Yay\layer;
use function Yay\ns;
use function Yay\optional;
use function Yay\token;

class ClassMethodParser extends AbstractParser
{
    public function parse(string $prefix = null): Parser
    {
        return chain(
            optional((new VisibilityModifiersParser())->parse(named("classMethod", $prefix))),
            buffer("function"),
            ns()->as(named("classMethodName", $prefix)),
            buffer("("),
            optional((new ArgumentsParser())->parse(named("classMethod", $prefix))),
            buffer(")"),
            optional((new ReturnTypeParser())->parse(named("classMethod", $prefix))),
            buffer("{"),
            layer()->as(named("classMethodBody", $prefix)),
            buffer("}")
        )
            ->as(named("classMethod", $prefix));
    }
}
