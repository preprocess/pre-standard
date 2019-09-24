<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;

use Yay\Parser;
use function Yay\buffer;
use function Yay\chain;
use function Yay\either;
use function Yay\expression;
use function Yay\layer;
use function Yay\ls;
use function Yay\ns;
use function Yay\optional;
use function Yay\repeat;
use function Yay\token;

class ClassTraitParser extends AbstractParser
{
    public function parse(): Parser
    {
        return chain(
            buffer("use"),
            ls(ns()->as("name"), buffer(","))->as("names"),
            either(
                chain(
                    buffer("{"),
                    repeat(
                        chain(
                            chain(
                                optional(token(T_STRING)->as("aliasLeftClass")),
                                optional(buffer("::")),
                                optional(token(T_STRING)->as("aliasLeftMethod"))
                            )->as("aliasLeft"),
                            either(
                                buffer("insteadof")->as("aliasInsteadOf"),
                                chain(buffer("as"), optional((new VisibilityModifiersParser())->parse()))->as("aliasAs")
                            ),
                            chain(
                                optional(token(T_STRING)->as("aliasRightClass")),
                                optional(buffer("::")),
                                optional(token(T_STRING)->as("aliasRightMethod"))
                            )->as("aliasRight"),
                            optional(buffer(";"))
                        )->as("alias")
                    )->as("aliases"),
                    buffer("}")
                )->as("body"),
                optional(buffer(";"))
            )
        )->as("trait");
    }
}
