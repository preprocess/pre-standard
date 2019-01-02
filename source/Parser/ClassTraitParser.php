<?php

namespace Pre\Standard\Parser;

use Pre\Standard\AbstractParser;
use function Pre\Standard\Internal\named;

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
    public function parse(string $prefix = null): Parser
    {
        return chain(
            buffer("use"),
            ls(ns()->as(named("classTraitName", $prefix)), buffer(","))->as(named("classTraitNames", $prefix)),
            either(
                chain(
                    buffer("{"),
                    repeat(
                        chain(
                            chain(
                                optional(token(T_STRING)->as(named("classTraitAliasLeftClass", $prefix))),
                                optional(buffer("::")),
                                optional(token(T_STRING)->as(named("classTraitAliasLeftMethod", $prefix)))
                            )->as(named("classTraitAliasLeft", $prefix)),
                            either(
                                buffer("insteadof")->as(named("classTraitAliasInsteadOf", $prefix)),
                                chain(
                                    buffer("as"),
                                    optional(
                                        (new VisibilityModifiersParser())->parse(named("classTraitAlias", $prefix))
                                    )
                                )->as(named("classTraitAliasAs", $prefix))
                            ),
                            chain(
                                optional(token(T_STRING)->as(named("classTraitAliasRightClass", $prefix))),
                                optional(buffer("::")),
                                optional(token(T_STRING)->as(named("classTraitAliasRightMethod", $prefix)))
                            )->as(named("classTraitAliasRight", $prefix)),
                            optional(buffer(";"))
                        )->as(named("classTraitAlias", $prefix))
                    )->as(named("classTraitAliases", $prefix)),
                    buffer("}")
                )->as(named("classTraitBody", $prefix)),
                optional(buffer(";"))
            )
        )
            ->as(named("classTrait", $prefix))
            ->onCommit($this->onCommit);
    }
}
