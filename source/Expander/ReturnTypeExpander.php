<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;
use Yay\Ast;
use Yay\Engine;

class ReturnTypeExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine, string $prefix = null)
    {
        $tokens = [":"];

        if (!empty($branch = $this->find($ast, named("returnNullableType", $prefix)))) {
            $tokens[] = (string) (new NullableTypeExpander())->expand(
                new Ast("", [named("returnNullableType", $prefix) => $branch]),
                $engine,
                named("return", $prefix)
            );
        }

        return streamed(aerated($tokens), $engine);
    }
}
