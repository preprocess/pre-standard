<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;
use function Pre\Standard\Internal\named;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\repeat;
use function Yay\either;
use function Yay\ls;
use function Yay\ns;
use function Yay\optional;

class ClassParser extends AbstractParser
{
    public function parse(string $prefix = null): Parser
    {
        return chain(
            buffer("class"),
            optional(ns()->as("className")),
            optional(
                repeat(
                    either(
                        chain(
                            buffer("extends"),
                            ns()->as(named("classExtendsType", $prefix))
                        )->as(named("classExtends", $prefix)),
                        chain(
                            buffer("implements"),
                            ls(
                                ns()->as(named("classImplementsType", $prefix)),
                                buffer(",")
                            )->as(named("classImplementsTypes", $prefix)),
                        )->as(named("classImplements", $prefix))
                    )
                )->as("classModifiers")
            ),
            buffer("{"),
            optional(
                repeat(
                    either(
                        (new ClassTraitParser())->parse($prefix),
                        (new ClassConstantParser())->parse($prefix),
                        (new ClassPropertyParser())->parse($prefix),
                        (new ClassMethodParser())->parse($prefix),
                    )
                )->as(named("classMembers", $prefix))
            ),
            buffer("}")
        )->as(named("class", $prefix));
    }
}
