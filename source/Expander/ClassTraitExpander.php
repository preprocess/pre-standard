<?php

namespace Pre\Standard\Expander;

use function join;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\TokenStream;

class ClassTraitExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        // TODO
    }
}
