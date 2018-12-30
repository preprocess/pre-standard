<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;
use function Pre\Standard\named;

use Yay\Parser;
use function Yay\buffer;
use function Yay\ls;

class ArgumentsParser extends AbstractParser
{
    public function parse(string $prefix = null): Parser
    {
        return ls((new ArgumentParser())->parse($prefix), buffer(","))
            ->as(named("arguments", $prefix))
            ->onCommit($this->onCommit);
    }
}
