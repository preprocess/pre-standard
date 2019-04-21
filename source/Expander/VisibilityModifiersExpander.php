<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;
use Yay\Ast;
use Yay\Engine;

class VisibilityModifiersExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine, string $prefix = null)
    {
        $tokens = [];
        $visibilityModifiers = $this->find($ast, named("visibilityModifiers", $prefix));

        foreach ($visibilityModifiers as $visibilityModifier) {
            $tokens[] = $this->find($visibilityModifier, named("visibilityModifier", $prefix));
        }

        return streamed(aerated($tokens), $engine);
    }
}
