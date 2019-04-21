<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;
use Yay\Ast;
use Yay\Engine;

class ClassConstantExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine, string $prefix = null)
    {
        $tokens = [];

        if (!empty($branch = $this->find($ast, named("classConstantVisibilityModifiers", $prefix)))) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                new Ast("", [named("classConstantVisibilityModifiers", $prefix) => $branch]),
                $engine,
                named("classConstant", $prefix)
            );
        }

        // DEBUG -> this returns strange things in the class parser
        if (empty($this->find($ast, named("classConstantValue", $prefix)))) {
            print_r($ast);
            exit;
        }

        $tokens[] = "const";
        $tokens[] = $this->find($ast, named("classConstantName", $prefix));
        $tokens[] = "=";
        $tokens[] = flattened($this->find($ast, named("classConstantValue", $prefix)));
        $tokens[] = ";";

        return streamed(aerated($tokens), $engine);
    }
}
