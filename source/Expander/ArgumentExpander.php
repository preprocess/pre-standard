<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\TokenStream;

class ArgumentExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);

        if (!empty($branch = $this->find($source, named("argumentNullableType", $prefix)))) {
            $tokens[] = (string) (new NullableTypeExpander())->expand(
                [named("argumentNullableType", $prefix) => $branch],
                $engine,
                named("argument", $prefix)
            );
        }

        $tokens[] = $this->find($source, named("argumentName", $prefix));

        if (!empty($branch = $this->find($source, named("argumentAssignment", $prefix)))) {
            $tokens[] = "=";

            if (!empty($this->find($branch, named("argumentNew", $prefix)))) {
                $tokens[] = "new";
            }

            $tokens[] = flattened($this->find($branch, named("argumentValue", $prefix)));
        }

        return streamed(aerated($tokens), $engine);
    }
}
