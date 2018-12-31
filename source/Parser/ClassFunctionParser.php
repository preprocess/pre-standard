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

class ClassFunctionParser extends AbstractParser
{
    public function parse(string $prefix = null): Parser
    {
        return chain(
            optional((new VisibilityModifiersParser())->parse(named("classFunction", $prefix))),
            buffer("function"),
            ns()->as(named("classFunctionName", $prefix)),
            buffer("("),
            optional((new ArgumentsParser())->parse(named("classFunction", $prefix))),
            buffer(")"),
            optional((new ReturnTypeParser())->parse(named("classFunction", $prefix))),
            buffer("{"),
            layer()->as(named("classFunctionBody", $prefix)),
            buffer("}")
        )
            ->as(named("classFunction", $prefix))
            ->onCommit($this->onCommit);
    }
}
