<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;
use function Pre\Standard\Internal\named;

use Yay\Parser;
use function Yay\buffer;
use function Yay\either;
use function Yay\ns;

class TypeParser extends AbstractParser
{
    public function parse(string $prefix = null): Parser
    {
        return either(ns(), buffer("array"), buffer("callable"), buffer("self"))
            ->as(named("type", $prefix))
            ->onCommit($this->onCommit);
    }
}
