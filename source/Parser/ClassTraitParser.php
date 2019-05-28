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
            ls(
                ns()->as("classTraitName"), buffer(",")
            )->as("classTraitNames"),
            either(
                chain(
                    buffer("{"),
                    repeat(
                        chain(
                            chain(
                                optional(token(T_STRING)->as("classTraitAliasLeftClass")),
                                optional(buffer("::")),
                                optional(token(T_STRING)->as("classTraitAliasLeftMethod"))
                            )->as("classTraitAliasLeft"),
                            either(
                                buffer("insteadof")->as("classTraitAliasInsteadOf"),
                                chain(
                                    buffer("as"),
                                    optional(
                                        (new VisibilityModifiersParser())->parse()
                                    )
                                )->as("classTraitAliasAs")
                            ),
                            chain(
                                optional(token(T_STRING)->as("classTraitAliasRightClass")),
                                optional(buffer("::")),
                                optional(token(T_STRING)->as("classTraitAliasRightMethod"))
                            )->as("classTraitAliasRight"),
                            optional(buffer(";"))
                        )->as("classTraitAlias")
                    )->as("classTraitAliases"),
                    buffer("}")
                )->as("classTraitBody"),
                optional(buffer(";"))
            )
        )->as("classTrait");
    }
}
