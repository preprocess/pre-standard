<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;
use Yay\Ast;
use Yay\Engine;

class TypeExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine, string $prefix = null)
    {
        $tokens = [];

        if (!empty($branch = $this->find($ast, named("type", $prefix)))) {
            $tokens[] = flattened($branch);
        }

        return streamed($tokens, $engine);
    }
}
