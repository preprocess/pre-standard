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

        if (!empty($branch = $this->find($source, named("classFunctionVisibilityModifiers", $prefix)))) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                [named("classFunctionVisibilityModifiers", $prefix) => $branch],
                $engine,
                "classFunction"
            );
        }

        $tokens[] = "function";
        $tokens[] = flattened($this->find($source, named("classFunctionName", $prefix)));
        $tokens[] = "(";

        if (!empty($branch = $this->find($source, named("classFunctionArguments", $prefix)))) {
            $tokens[] = (string) (new ArgumentsExpander())->expand(
                [named("classFunctionArguments", $prefix) => $branch],
                $engine,
                "classFunction"
            );
        }

        $tokens[] = ")";

        if (!empty($branch = $this->find($source, named("classFunctionReturnType", $prefix)))) {
            $tokens[] = (string) (new ReturnTypeExpander())->expand(
                [named("classFunctionReturnType", $prefix) => $branch],
                $engine,
                named("classFunction", $prefix)
            );
        }

        $tokens[] = "{";

        if (!empty($branch = $this->find($source, named("classFunctionBody", $prefix)))) {
            $tokens[] = flattened($branch);
        }

        $tokens[] = "}";

        return streamed(aerated($tokens), $engine);
    }
}
