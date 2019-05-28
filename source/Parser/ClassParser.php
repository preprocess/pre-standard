<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\layer;
use function Yay\repeat;
use function Yay\either;
use function Yay\ls;
use function Yay\ns;
use function Yay\optional;
use function Yay\token;

class ClassParser extends AbstractParser
{
    public function parse(): Parser
    {
        return chain(
            either(
                chain(
                    buffer("new"),
                    buffer("class"),
                    optional(buffer("(")),
                    optional(layer())->as("classArguments"),
                    optional(buffer(")"))
                )->as("classAnonymous"),
                chain(
                    buffer("class"),
                    ns()->as("className"),
                )->as("classNamed"),
            ),
            optional(
                repeat(
                    either(
                        chain(
                            buffer("extends"),
                            ns()->as("classExtendsType")
                        ),
                        chain(
                            buffer("implements"),
                            ls(
                                ns()->as("classImplementsType"),
                                buffer(",")
                            )->as("classImplementsTypes"),
                        )
                    )
                )->as("classModifiers")
            ),
            buffer("{"),
            optional(
                repeat(
                    either(
                        (new ClassTraitParser())->parse(),
                        (new ClassConstantParser())->parse(),
                        (new ClassPropertyParser())->parse(),
                        (new ClassMethodParser())->parse()
                    )
                )->as("classMembers")
            ),
            buffer("}")
        )->as("cls");
    }
}
