<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

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
    public function parse(): Parser
    {
        return chain(
            optional(
                (new VisibilityModifiersParser())->parse()
            ),
            buffer("function"),
            ns()->as("classMethodName"),
            buffer("("),
            optional(
                (new ArgumentsParser())->parse()
            ),
            buffer(")"),
            optional(
                (new ReturnTypeParser())->parse()
            ),
            buffer("{"),
            layer()->as("classMethodBody"),
            buffer("}")
        )->as("classMethod");
    }
}
