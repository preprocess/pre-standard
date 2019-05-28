<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class VisibilityModifiersExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine): TokenStream
    {
        $tokens = [];
        $visibilityModifiers = $this->find($ast, "visibilityModifiers");

        foreach ($visibilityModifiers as $visibilityModifier) {
            $tokens[] = $this->find($visibilityModifier, "visibilityModifier");
        }

        return streamed(aerated($tokens), $engine);
    }
}
