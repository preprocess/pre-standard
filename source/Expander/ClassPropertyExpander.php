<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class ClassPropertyExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine): TokenStream
    {
        $tokens = [];

        if (!empty(($branch = $this->find($ast, "visibilityModifiers")))) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                new Ast("", ["visibilityModifiers" => $branch]),
                $engine
            );
        }

        if (!empty(($branch = $this->find($ast, "nullableType")))) {
            $tokens[] = (string) (new NullableTypeExpander())->expand(
                new Ast("", ["nullableType" => $branch]),
                $engine
            );
        }

        $tokens[] = $this->find($ast, "name");
        $tokens[] = "=";
        $tokens[] = flattened($this->find($ast, "value"));
        $tokens[] = ";";

        return streamed(aerated($tokens), $engine);
    }
}
