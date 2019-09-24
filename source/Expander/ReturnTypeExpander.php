<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class ReturnTypeExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine): TokenStream
    {
        $tokens = [":"];

        if (!empty(($branch = $this->find($ast, "nullableType")))) {
            $tokens[] = (string) (new NullableTypeExpander())->expand(
                new Ast("", ["nullableType" => $branch]),
                $engine
            );
        }

        return streamed(aerated($tokens), $engine);
    }
}
