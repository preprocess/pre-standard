<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;
use Yay\Ast;
use Yay\Engine;

class ArgumentsExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine, string $prefix = null)
    {
        $tokens = [];
        $arguments = $this->find($ast, named("arguments", $prefix));

        foreach ($arguments as $argument) {
            $tokens[] = (string) (new ArgumentExpander())->expand(
                new Ast("", [named("argument", $prefix) => $argument]),
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
