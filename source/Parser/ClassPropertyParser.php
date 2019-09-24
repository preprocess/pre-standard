<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\expression;
use function Yay\optional;
use function Yay\token;

class ClassPropertyParser extends AbstractParser
{
    public function parse(): Parser
    {
        return chain(
            (new VisibilityModifiersParser())->parse(),
            optional((new NullableTypeParser())->parse()),
            token(T_VARIABLE)->as("name"),
            optional(buffer("=")),
            optional(expression())->as("value"),
            optional(buffer(";"))
        )->as("property");
    }
}
