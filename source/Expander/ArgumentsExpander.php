<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class ArgumentsExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine): TokenStream
    {
        $tokens = [];
        $arguments = $this->find($ast, "arguments");

        foreach ($arguments as $argument) {
            $tokens[] = (string) (new ArgumentExpander())->expand(new Ast("", ["argument" => $argument]), $engine);

            $tokens[] = ", ";
        }

        // ...trailing comma shouldn't hurt, but this will
        // reduce processing time in large quantities of code
        array_pop($tokens);

        return streamed($tokens, $engine);
    }
}
