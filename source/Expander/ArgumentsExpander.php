<?php

namespace Pre\Standard\Expander;

use function join;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\aerated;
use function Pre\Standard\flatten;
use function Pre\Standard\match;
use function Pre\Standard\streamed;

use Yay\Engine;
use Yay\TokenStream;

class ArgumentsExpander extends AbstractExpander
{
    public function expand($source, Engine $engine): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);

        foreach ($source as $argument) {
            $tokens[] = (string) (new ArgumentExpander())->expand($argument["argument"], $engine);
            $tokens[] = ", ";
        }

        array_pop($tokens);

        return streamed($tokens, $engine);
    }
}
