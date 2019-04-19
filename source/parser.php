<?php

namespace Pre\Standard\Parser;

use Pre\Standard\Parser\ArgumentParser;
use Pre\Standard\Parser\ArgumentsParser;
use Pre\Standard\Parser\ClassConstantParser;
use Pre\Standard\Parser\ClassMethodParser;
use Pre\Standard\Parser\ClassParser;
use Pre\Standard\Parser\ClassPropertyParser;
use Pre\Standard\Parser\ClassTraitParser;
use Pre\Standard\Parser\NullableTypeParser;
use Pre\Standard\Parser\TypeParser;
use Pre\Standard\Parser\VisibilityModifiersParser;

use Yay\Parser;

function argument(string $prefix = null): Parser
{
    return (new ArgumentParser())->parse($prefix);
}

function arguments(string $prefix = null): Parser
{
    return (new ArgumentsParser())->parse($prefix);
}

function cls(string $prefix = null): Parser
{
    return (new ClassParser())->parse($prefix);
}

function classConstant(string $prefix = null): Parser
{
    return (new ClassConstantParser())->parse($prefix);
}

function classMethod(string $prefix = null): Parser
{
    return (new ClassMethodParser())->parse($prefix);
}

function classProperty(string $prefix = null): Parser
{
    return (new ClassPropertyParser())->parse($prefix);
}

function classTrait(string $prefix = null): Parser
{
    return (new ClassTraitParser())->parse($prefix);
}

function nullableType(string $prefix = null): Parser
{
    return (new NullableTypeParser())->parse($prefix);
}

function type(string $prefix = null): Parser
{
    return (new TypeParser())->parse($prefix);
}

function visibilityModifiers(string $prefix = null): Parser
{
    return (new VisibilityModifiersParser())->parse($prefix);
}
