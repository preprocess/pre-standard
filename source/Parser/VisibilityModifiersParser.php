<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;
use function Pre\Standard\Internal\named;

use Yay\Parser;
use function Yay\buffer;
use function Yay\either;
use function Yay\repeat;

class VisibilityModifiersParser extends AbstractParser
{
    public function parse(string $prefix = null): Parser
    {
        return repeat(
            either(
                buffer("abstract"),
                buffer("public"),
                buffer("protected"),
                buffer("private"),
                buffer("static")
            )->as(named("visibilityModifier", $prefix))
        )->as(named("visibilityModifiers", $prefix));
    }
}
