<?php

namespace Pre\Standard\Expander;

use Pre\Standard\Expander\ArgumentExpander;
use Pre\Standard\Expander\ArgumentsExpander;
use Pre\Standard\Expander\ClassConstantExpander;
use Pre\Standard\Expander\ClassMethodExpander;
use Pre\Standard\Expander\ClassPropertyExpander;
use Pre\Standard\Expander\ClassTraitExpander;
use Pre\Standard\Expander\NullableTypeExpander;
use Pre\Standard\Expander\TypeExpander;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

function argument(Ast $ast, Engine $engine): TokenStream
{
    return (new ArgumentExpander())->expand($ast, $engine);
}

function arguments(Ast $ast, Engine $engine): TokenStream
{
    return (new ArgumentsExpander())->expand($ast, $engine);
}

function classConstant(Ast $ast, Engine $engine): TokenStream
{
    return (new ClassConstantExpander())->expand($ast, $engine);
}

function classMethod(Ast $ast, Engine $engine): TokenStream
{
    return (new ClassMethodExpander())->expand($ast, $engine);
}

function classProperty(Ast $ast, Engine $engine): TokenStream
{
    return (new ClassPropertyExpander())->expand($ast, $engine);
}

function classTrait(Ast $ast, Engine $engine): TokenStream
{
    return (new ClassTraitExpander())->expand($ast, $engine);
}

function nullableType(Ast $ast, Engine $engine): TokenStream
{
    return (new NullableTypeExpander())->expand($ast, $engine);
}

function studly(Ast $ast, Engine $engine): TokenStream
{
    $stream = \str_replace(["-", "_"], " ", $stream);
    $stream = \str_replace(" ", "", \ucwords($stream));

    return streamed($ast, $engine);
}

function type(Ast $ast, Engine $engine): TokenStream
{
    return (new TypeExpander())->expand($ast, $engine);
}

function visibilityModifiers(Ast $ast, Engine $engine): TokenStream
{
    return (new VisibilityModifiersExpander())->expand($ast, $engine);
}
