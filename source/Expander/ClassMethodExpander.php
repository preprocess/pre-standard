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

class ClassMethodExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);

        if (!empty($branch = $this->find($source, named("classMethodVisibilityModifiers", $prefix)))) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                [named("classMethodVisibilityModifiers", $prefix) => $branch],
                $engine,
                "classMethod"
            );
        }

        $tokens[] = "function";
        $tokens[] = flattened($this->find($source, named("classMethodName", $prefix)));
        $tokens[] = "(";

        if (!empty($branch = $this->find($source, named("classMethodArguments", $prefix)))) {
            $tokens[] = (string) (new ArgumentsExpander())->expand(
                [named("classMethodArguments", $prefix) => $branch],
                $engine,
                "classMethod"
            );
        }

        $tokens[] = ")";

        if (!empty($branch = $this->find($source, named("classMethodReturnType", $prefix)))) {
            $tokens[] = (string) (new ReturnTypeExpander())->expand(
                [named("classMethodReturnType", $prefix) => $branch],
                $engine,
                named("classMethod", $prefix)
            );
        }

        $tokens[] = "{";

        if (!empty($branch = $this->find($source, named("classMethodBody", $prefix)))) {
            $tokens[] = flattened($branch);
        }

        $tokens[] = "}";

        return streamed(aerated($tokens), $engine);
    }
}
