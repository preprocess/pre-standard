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

function argument(): Parser
{
    return (new ArgumentParser())->parse();
}

function arguments(): Parser
{
    return (new ArgumentsParser())->parse();
}

function cls(): Parser
{
    return (new ClassParser())->parse();
}

function classConstant(): Parser
{
    return (new ClassConstantParser())->parse();
}

function classMethod(): Parser
{
    return (new ClassMethodParser())->parse();
}

function classProperty(): Parser
{
    return (new ClassPropertyParser())->parse();
}

function classTrait(): Parser
{
    return (new ClassTraitParser())->parse();
}

function nullableType(): Parser
{
    return (new NullableTypeParser())->parse();
}

function type(): Parser
{
    return (new TypeParser())->parse();
}

function visibilityModifiers(): Parser
{
    return (new VisibilityModifiersParser())->parse();
}
