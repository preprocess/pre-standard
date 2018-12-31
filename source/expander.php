<?php

namespace Pre\Standard\Expander;

use Pre\Standard\Expander\ArgumentExpander;
use Pre\Standard\Expander\ArgumentsExpander;
use Pre\Standard\Expander\NullableTypeExpander;
use Pre\Standard\Expander\TypeExpander;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\TokenStream;

function argument(TokenStream $stream, Engine $engine): TokenStream
{
    return (new ArgumentExpander())->expand($stream, $engine);
}

function arguments(TokenStream $stream, Engine $engine): TokenStream
{
    return (new ArgumentsExpander())->expand($stream, $engine);
}

function nullableType(TokenStream $stream, Engine $engine): TokenStream
{
    return (new NullableTypeExpander())->expand($stream, $engine);
}

function studly(TokenStream $stream, Engine $engine): TokenStream
{
    $stream = \str_replace(["-", "_"], " ", $stream);
    $stream = \str_replace(" ", "", \ucwords($stream));

    return streamed($stream, $engine);
}

function type(TokenStream $stream, Engine $engine): TokenStream
{
    return (new TypeExpander())->expand($stream, $engine);
}