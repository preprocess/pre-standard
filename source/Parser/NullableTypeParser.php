<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\optional;

class NullableTypeParser extends AbstractParser
{
    public function parse(): Parser
    {
        return chain(
            optional(buffer("?"))->as("nullable"),
            (new TypeParser())->parse()
        )->as("nullableType");
    }
}
