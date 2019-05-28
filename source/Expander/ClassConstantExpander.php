<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class ClassConstantExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine): TokenStream
    {
        $tokens = [];

        if (!empty($branch = $this->find($ast, "visibilityModifiers"))) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                new Ast("", ["visibilityModifiers" => $branch]),
                $engine
            );
        }

        $tokens[] = "const";
        $tokens[] = $this->find($ast, "classConstantName");
        $tokens[] = "=";
        $tokens[] = flattened($this->find($ast, "classConstantValue"));
        $tokens[] = ";";

        return streamed(aerated($tokens), $engine);
    }
}
