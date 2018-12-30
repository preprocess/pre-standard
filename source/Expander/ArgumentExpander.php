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
    public function expand($source, Engine $engine): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);

        if (!empty($source["argumentNullableType"])) {
            if (!empty($source["argumentNullableType"]["argumentNullable"])) {
                $tokens[] = "?";
            }

            if (!empty($source["argumentNullableType"]["argumentType"])) {
                $tokens[] = flatten($source["argumentNullableType"]["argumentType"]);
            }
        }

        $tokens[] = $source["argumentName"];

        if (!empty($source["argumentAssignment"])) {
            $tokens[] = "=";

            if (!empty($source["argumentAssignment"]["argumentNew"])) {
                $tokens[] = "new";
            }

            $tokens[] = flatten($source["argumentAssignment"]["argumentValue"]);
        }

        return streamed(aerated($tokens), $engine);
    }
}
