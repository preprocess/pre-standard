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

        if (!empty($branch = $this->find($source, named("classConstantVisibilityModifiers", $prefix)))) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                [named("classConstantVisibilityModifiers", $prefix) => $branch],
                $engine,
                named("classConstant", $prefix)
            );
        }

        $tokens[] = "const";
        $tokens[] = $this->find($source, named("classConstantName", $prefix));
        $tokens[] = "=";
        $tokens[] = flattened($this->find($source, named("classConstantValue", $prefix)));
        $tokens[] = ";";

        return streamed(aerated($tokens), $engine);
    }
}
