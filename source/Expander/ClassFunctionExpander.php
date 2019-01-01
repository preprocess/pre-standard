<?php

namespace Pre\Standard\Expander;

use function join;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\TokenStream;

class ClassFunctionExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);

        if (!empty($source[named("classFunctionVisibilityModifiers", $prefix)])) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                $source[named("classFunctionVisibilityModifiers", $prefix)],
                $engine,
                "classFunction"
            );
        }

        $tokens[] = "function";
        $tokens[] = flattened($source[named("classFunctionName", $prefix)]);
        $tokens[] = "(";

        if (!empty($source[named("classFunctionVisibilityModifiers", $prefix)])) {
            $tokens[] = (string) (new ArgumentsExpander())->expand(
                $source[named("classFunctionArguments", $prefix)],
                $engine,
                "classFunction"
            );
        }

        $tokens[] = ")";

        if (!empty($source[named("classFunctionReturnType", $prefix)])) {
            $tokens[] = (string) (new ReturnTypeExpander())->expand(
                $source[named("classFunctionReturnType", $prefix)],
                $engine,
                "classFunction"
            );
        }

        $tokens[] = "{";

        if (!empty($source[named("classFunctionBody", $prefix)])) {
            $tokens[] = flattened($source[named("classFunctionBody", $prefix)]);
        }

        $tokens[] = "}";

        return streamed(aerated($tokens), $engine);
    }
}
