<?php

namespace Pre\Standard\Expander;

use function join;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flatten;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\Token;
use Yay\TokenStream;

class TypeExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $source = $this->resolve($source);
        $token = new Token(T_STRING, flatten($source));

        return streamed([$token], $engine);
    }
}
