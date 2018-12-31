<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flatten;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\TokenStream;

class ArgumentExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);

        if (!empty($source[named("argumentNullableType", $prefix)])) {
            if (!empty($source[named("argumentNullableType", $prefix)][named("argumentNullable", $prefix)])) {
                $tokens[] = "?";
            }

            if (!empty($source[named("argumentNullableType", $prefix)][named("argumentType", $prefix)])) {
                $tokens[] = flatten($source[named("argumentNullableType", $prefix)][named("argumentType", $prefix)]);
            }
        }

        $tokens[] = $source[named("argumentName", $prefix)];

        if (!empty($source[named("argumentAssignment", $prefix)])) {
            $tokens[] = "=";

            if (!empty($source[named("argumentAssignment", $prefix)][named("argumentNew", $prefix)])) {
                $tokens[] = "new";
            }

            $tokens[] = flatten($source[named("argumentAssignment", $prefix)][named("argumentValue", $prefix)]);
        }

        return streamed(aerated($tokens), $engine);
    }
}
