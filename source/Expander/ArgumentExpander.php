<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\aerated;
use function Pre\Standard\flatten;
use function Pre\Standard\match;
use function Pre\Standard\streamed;

use Yay\Engine;
use Yay\TokenStream;

class ArgumentExpander extends AbstractExpander
{
    public function expand(TokenStream $stream, Engine $engine): TokenStream
    {
        $tokens = [];

        $ast = match($stream)->unwrap();

        if (!empty($ast["argumentNullable"])) {
            $tokens[] = "?";
        }

        if (!empty($ast["argumentType"])) {
            $tokens[] = flatten($ast["argumentType"]);
        }

        $tokens[] = $ast["argumentName"];

        if (!empty($ast["argumentAssignment"])) {
            $tokens[] = "=";

            if (!empty($ast["argumentAssignment"]["argumentNew"])) {
                $tokens[] = "new";
            }

            $tokens[] = flatten($ast["argumentAssignment"]["argumentValue"]);
        }

        return streamed(aerated($tokens), $engine);
    }
}
