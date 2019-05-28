<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\expression;
use function Yay\optional;
use function Yay\token;

class ClassConstantParser extends AbstractParser
{
    public function parse(): Parser
    {
        return chain(
            optional(
                (new VisibilityModifiersParser())->parse()
            ),
            buffer("const"),
            token(T_STRING)->as("classConstantName"),
            buffer("="),
            expression()->as("classConstantValue"),
            optional(buffer(";"))
        )
            ->as("classConstant");
    }
}
