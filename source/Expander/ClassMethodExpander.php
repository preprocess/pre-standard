<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;
use Yay\Ast;
use Yay\Engine;

class ClassMethodExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine, string $prefix = null)
    {
        $tokens = [];

        if (!empty($branch = $this->find($ast, named("classMethodVisibilityModifiers", $prefix)))) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                new Ast("", [named("classMethodVisibilityModifiers", $prefix) => $branch]),
                $engine,
                "classMethod"
            );
        }

        $tokens[] = "function";
        $tokens[] = flattened($this->find($ast, named("classMethodName", $prefix)));
        $tokens[] = "(";

        if (!empty($branch = $this->find($ast, named("classMethodArguments", $prefix)))) {
            $tokens[] = (string) (new ArgumentsExpander())->expand(
                new Ast("", [named("classMethodArguments", $prefix) => $branch]),
                $engine,
                "classMethod"
            );
        }

        $tokens[] = ")";

        if (!empty($branch = $this->find($ast, named("classMethodReturnType", $prefix)))) {
            $tokens[] = (string) (new ReturnTypeExpander())->expand(
                new Ast("", [named("classMethodReturnType", $prefix) => $branch]),
                $engine,
                named("classMethod", $prefix)
            );
        }

        $tokens[] = "{";

        if (!empty($branch = $this->find($ast, named("classMethodBody", $prefix)))) {
            $tokens[] = flattened($branch);
        }

        $tokens[] = "}";

        return streamed(aerated($tokens), $engine);
    }
}
