<?php

namespace Pre\Standard\Expander;

use function join;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\TokenStream;

class ClassConstantExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);

        if (!empty($source[named("classConstantVisibilityModifiers", $prefix)])) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                $source[named("classConstantVisibilityModifiers", $prefix)],
                $engine,
                "classConstant"
            );
        }

        $tokens[] = "const";
        $tokens[] = $source[named("classConstantName", $prefix)];
        $tokens[] = "=";
        $tokens[] = flattened($source[named("classConstantValue", $prefix)]);
        $tokens[] = ";";

        return streamed(aerated($tokens), $engine);
    }
}
