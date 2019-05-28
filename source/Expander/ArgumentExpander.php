<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class ArgumentExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine): TokenStream
    {
        $tokens = [];

        if (!empty($branch = $this->find($ast, "nullableType"))) {
            $tokens[] = (string) (new NullableTypeExpander())->expand(
                new Ast("", ["nullableType" => $branch]),
                $engine
            );
        }

        $tokens[] = $this->find($ast, "argumentName");

        if (!empty($branch = $this->find($ast, "argumentAssignment"))) {
            $tokens[] = "=";

            if (!empty($this->find($branch, "argumentNew"))) {
                $tokens[] = "new";
            }

            $tokens[] = flattened($this->find($branch, "argumentValue"));
        }

        return streamed(aerated($tokens), $engine);
    }
}
