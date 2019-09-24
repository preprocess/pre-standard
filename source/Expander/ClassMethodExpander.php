<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class ClassMethodExpander extends AbstractExpander
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

        $tokens[] = "function";
        $tokens[] = flattened($this->find($ast, "name"));
        $tokens[] = "(";

        if (!empty(($branch = $this->find($ast, "arguments")))) {
            $tokens[] = (string) (new ArgumentsExpander())->expand(new Ast("", ["arguments" => $branch]), $engine);
        }

        $tokens[] = ")";

        if (!empty(($branch = $this->find($ast, "returnType")))) {
            $tokens[] = (string) (new ReturnTypeExpander())->expand(new Ast("", ["returnType" => $branch]), $engine);
        }

        $tokens[] = "{";

        if (!empty(($branch = $this->find($ast, "body")))) {
            $tokens[] = flattened($branch);
        }

        $tokens[] = "}";

        return streamed(aerated($tokens), $engine);
    }
}
