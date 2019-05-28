<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

use Yay\Parser;
use function Yay\buffer;
use function Yay\either;
use function Yay\repeat;

class VisibilityModifiersParser extends AbstractParser
{
    public function parse(): Parser
    {
        return repeat(
            either(
                buffer("abstract"),
                buffer("public"),
                buffer("protected"),
                buffer("private"),
                buffer("static")
            )->as("visibilityModifier")
        )->as("visibilityModifiers");
    }
}
