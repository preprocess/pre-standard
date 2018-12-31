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

        if (!empty($source[named("classPropertyVisibilityModifiers", $prefix)])) {
            $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                $source[named("classPropertyVisibilityModifiers", $prefix)],
                $engine,
                "classProperty"
            );
        }

        if (!empty($source[named("classPropertyNullableType", $prefix)])) {
            $tokens[] = (string) (new NullableTypeExpander())->expand(
                $source[named("classPropertyNullableType", $prefix)],
                $engine,
                "classProperty"
            );
        }

        $tokens[] = $source[named("classPropertyName", $prefix)];
        $tokens[] = "=";
        $tokens[] = flattened($source[named("classPropertyValue", $prefix)]);
        $tokens[] = ";";

        return streamed(aerated($tokens), $engine);
    }
}
