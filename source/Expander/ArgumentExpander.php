<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\flatten;
use function Pre\Standard\match;
use function Pre\Standard\streamed;
use function Pre\Standard\aerated;

use Yay\Engine;
use Yay\TokenStream;

class ArgumentExpander extends AbstractExpander
{
    public function expand(TokenStream $stream, Engine $engine): TokenStream
    {
        $ast = match($stream)->unwrap();

        $tokens = [];

        if (!empty($ast["argumentNullable"])) {
            array_push($tokens, $ast["argumentNullable"]);
        }

        if (!empty($ast["argumentType"])) {
            array_push($tokens, flatten($ast["argumentType"]));
        }

        array_push($tokens, $ast["argumentName"]);

        if (!empty($ast["argumentValue"])) {
            array_push($tokens, "=");

            if (!empty($ast["argumentNew"])) {
                array_push($tokens, $ast["argumentNew"]);
            }

            array_push($tokens, flatten($ast["argumentValue"]));
        }

        return streamed(aerated($tokens), $engine);
    }
}
