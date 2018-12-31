<?php

namespace Pre\Standard\Expander;

use function join;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\TokenStream;

class ArgumentsExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);

        foreach ($source as $argument) {
            $tokens[] = (string) (new ArgumentExpander())->expand(
                $argument[named("argument", $prefix)],
                $engine,
                $prefix
            );
            $tokens[] = ", ";
        }

        // ...trailing comma shouldn't hurt, but this will
        // reduce processing time in large quantities of code
        array_pop($tokens);

        return streamed($tokens, $engine);
    }
}
