<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class TypeExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine): TokenStream
    {
        $tokens = [];

        if (!empty(($branch = $this->find($ast, "type")))) {
            $tokens[] = flattened($branch);
        }

        return streamed($tokens, $engine);
    }
}
