<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

use Yay\Parser;
use function Yay\between;
use function Yay\buffer;
use function Yay\chain;
use function Yay\layer;
use function Yay\repeat;
use function Yay\either;
use function Yay\expression;
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
                    optional(
                        between(buffer("("), optional(ls(expression()->as("argument"), buffer(","))), buffer(")"))->as(
                            "arguments"
                        )
                    )
                )->as("anonymous"),
                chain(buffer("class"), ns()->as("name"))->as("named")
            ),
            optional(
                repeat(
                    either(
                        chain(buffer("extends"), ns()->as("extendsType")),
                        chain(buffer("implements"), ls(ns()->as("implementsType"), buffer(","))->as("implementsTypes"))
                    )
                )->as("modifiers")
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
                )->as("members")
            ),
            buffer("}")
        )->as("clas");
    }
}
