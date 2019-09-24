<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class ClassExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine): TokenStream
    {
        $tokens = [];

        if (!empty(($anonymous = $this->find($ast, "anonymous")))) {
            $tokens[] = "new class";

            if (!empty($anonymous["arguments"])) {
                $tokens[] = "(";

                foreach ($anonymous["arguments"] as $argument) {
                    $tokens[] = flattened($argument);
                    $tokens[] = ",";
                }

                array_pop($tokens);

                $tokens[] = ")";
            }
        }

        if (!empty(($named = $this->find($ast, "named")))) {
            $tokens[] = "class";
            $tokens[] = flattened($named["name"]);
        }

        $tokens[] = "{";

        foreach ($this->find($ast, "members") as $member) {
            if (!empty($member["constant"])) {
                $tokens[] = (string) (new ClassConstantExpander())->expand(new Ast("", $member), $engine);
            }

            if (!empty($member["method"])) {
                $tokens[] = (string) (new ClassMethodExpander())->expand(new Ast("", $member), $engine);
            }

            if (!empty($member["property"])) {
                $tokens[] = (string) (new ClassPropertyExpander())->expand(new Ast("", $member), $engine);
            }

            if (!empty($member["trait"])) {
                $tokens[] = (string) (new ClassTraitExpander())->expand(new Ast("", $member), $engine);
            }
        }

        $tokens[] = "}";

        return streamed(aerated($tokens), $engine);
    }
}
