<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;
use function Pre\Standard\Internal\named;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\optional;

class NullableTypeParser extends AbstractParser
{
    public function parse(string $prefix = null): Parser
    {
        return chain(optional(buffer("?"))->as(named("nullable", $prefix)), (new TypeParser())->parse($prefix))
            ->as(named("nullableType", $prefix))
            ->onCommit($this->onCommit);
    }
}
