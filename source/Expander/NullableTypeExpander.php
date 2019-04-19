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

class NullableTypeExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = [];
        $source = $this->resolve($source);

        if (!empty($this->find($source, named("nullable", $prefix)))) {
            $tokens[] = "?";
        }

        // ...seems when the nullable is missing
        // type is sometimes not named or nested
        if (empty($branch = $this->find($source, named("type", $prefix)))) {
            $tokens[] = flattened($source);
        } else {
            $tokens[] = (string) (new TypeExpander())->expand(
                [named("type", $prefix) => $branch],
                $engine,
                $prefix
            );
        }

        return streamed(aerated($tokens), $engine);
    }
}
