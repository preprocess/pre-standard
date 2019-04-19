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

class ClassPropertyExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);

        if (!empty($branch = $this->find($source, named("classPropertyVisibilityModifiers", $prefix)))) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                [named("classPropertyVisibilityModifiers", $prefix) => $branch],
                $engine,
                named("classProperty", $prefix)
            );
        }

        if (!empty($branch = $this->find($source, named("classPropertyNullableType", $prefix)))) {
            $tokens[] = (string) (new NullableTypeExpander())->expand(
                [named("classPropertyNullableType", $prefix) => $branch],
                $engine,
                "classProperty"
            );
        }

        $tokens[] = $this->find($source, named("classPropertyName", $prefix));
        $tokens[] = "=";
        $tokens[] = flattened($this->find($source, named("classPropertyValue", $prefix)));
        $tokens[] = ";";

        return streamed(aerated($tokens), $engine);
    }
}
