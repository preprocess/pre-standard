<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;
use Yay\Ast;
use Yay\Engine;

class ArgumentExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine, string $prefix = null)
    {
        $tokens = [];

        if (!empty($branch = $this->find($ast, named("argumentNullableType", $prefix)))) {
            $tokens[] = (string) (new NullableTypeExpander())->expand(
                new Ast("", [named("argumentNullableType", $prefix) => $branch]),
                $engine,
                named("argument", $prefix)
            );
        }

        $tokens[] = $this->find($ast, named("argumentName", $prefix));

        if (!empty($branch = $this->find($ast, named("argumentAssignment", $prefix)))) {
            $tokens[] = "=";

            if (!empty($this->find($branch, named("argumentNew", $prefix)))) {
                $tokens[] = "new";
            }

            $tokens[] = flattened($this->find($branch, named("argumentValue", $prefix)));
        }

        return streamed(aerated($tokens), $engine);
    }
}
