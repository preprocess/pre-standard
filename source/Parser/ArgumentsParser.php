<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\ls;

class ArgumentsParser extends AbstractParser
{
    public function parse(): Parser
    {
        return ls(
            (new ArgumentParser())->parse(),
            buffer(",")
        )->as("arguments");
    }
}
