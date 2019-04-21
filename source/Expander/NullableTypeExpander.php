<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;
use Yay\Ast;
use Yay\Engine;

class NullableTypeExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine, string $prefix = null)
    {
        $tokens = [];

        if (!empty($this->find($ast, named("nullable", $prefix)))) {
            $tokens[] = "?";
        }

        if (!empty($branch = $this->find($ast, named("type", $prefix)))) {
            $tokens[] = (string) (new TypeExpander())->expand(
                new Ast("", [named("type", $prefix) => $branch]),
                $engine,
                $prefix
            );
        }

        return streamed(aerated($tokens), $engine);
    }
}
