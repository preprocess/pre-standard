<?php

namespace Pre\Standard\Expander;

use function join;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\TokenStream;

class ReturnTypeExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = [":"];
        $source = $this->resolve($source);

        if (!empty($branch = $this->find($source, named("returnNullableType", $prefix)))) {
            $tokens[] = (string) (new NullableTypeExpander())->expand(
                [named("returnNullableType", $prefix) => $branch],
                $engine,
                named("return", $prefix)
            );
        }

        return streamed(aerated($tokens), $engine);
    }
}
