<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class NullableTypeExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine): TokenStream
    {
        $tokens = [];

        if (!empty($this->find($ast, "nullable"))) {
            $tokens[] = "?";
        }

        if (!empty($branch = $this->find($ast, "type"))) {
            $tokens[] = (string) (new TypeExpander())->expand(
                new Ast("", ["type" => $branch]),
                $engine
            );
        }

        return streamed(aerated($tokens), $engine);
    }
}
