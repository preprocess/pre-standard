<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;
use Yay\Ast;
use Yay\Engine;

class ClassPropertyExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine, string $prefix = null)
    {
        $tokens = [];

        if (!empty($branch = $this->find($ast, named("classPropertyVisibilityModifiers", $prefix)))) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                new Ast("", [named("classPropertyVisibilityModifiers", $prefix) => $branch]),
                $engine,
                named("classProperty", $prefix)
            );
        }

        if (!empty($branch = $this->find($ast, named("classPropertyNullableType", $prefix)))) {
            $tokens[] = (string) (new NullableTypeExpander())->expand(
                new Ast("", [named("classPropertyNullableType", $prefix) => $branch]),
                $engine,
                "classProperty"
            );
        }

        $tokens[] = $this->find($ast, named("classPropertyName", $prefix));
        $tokens[] = "=";
        $tokens[] = flattened($this->find($ast, named("classPropertyValue", $prefix)));
        $tokens[] = ";";

        return streamed(aerated($tokens), $engine);
    }
}
