<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\either;
use function Yay\ns;

class TypeParser extends AbstractParser
{
    public function parse(): Parser
    {
        return either(ns(), buffer("array"), buffer("callable"), buffer("self"))->as("type");
    }
}
