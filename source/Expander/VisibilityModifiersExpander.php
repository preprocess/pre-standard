<?php

namespace Pre\Standard\Expander;

use function join;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\TokenStream;

class VisibilityModifiersExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);
        $visibilityModifiers = $this->find($source, named("visibilityModifiers", $prefix));

        foreach ($visibilityModifiers as $visibilityModifier) {
            $tokens[] = $this->find($visibilityModifier, named("visibilityModifier", $prefix));
        }

        return streamed(aerated($tokens), $engine);
    }
}
