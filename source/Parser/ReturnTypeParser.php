<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;

class ReturnTypeParser extends AbstractParser
{
    public function parse(): Parser
    {
        return chain(
            buffer(":"),
            (new NullableTypeParser())->parse()
        )->as("returnType");
    }
}
