<?php

namespace Pre\Standard\Expander;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\streamed;

use Yay\Ast;
use Yay\Engine;
use Yay\TokenStream;

class ClassTraitExpander extends AbstractExpander
{
    public function expand(Ast $ast, Engine $engine): TokenStream
    {
        $tokens = ["use"];

        foreach ($this->find($ast, "names") as $classTraitName) {
            $tokens[] = flattened($classTraitName);
            $tokens[] = ",";
        }

        array_pop($tokens);

        if (!empty(($branch = $this->find($ast, "body")))) {
            $tokens[] = "{";

            foreach (($leaf = $this->find($branch, "aliases")) as $classTraitAlias) {
                $alias = $classTraitAlias["alias"];

                if (!empty($alias["aliasLeft"]["aliasLeftClass"])) {
                    $tokens[] = $alias["aliasLeft"]["aliasLeftClass"];
                }

                if (!empty($alias["aliasLeft"]["aliasLeftClass"]) && !empty($alias["aliasLeft"]["aliasLeftMethod"])) {
                    $tokens[] = "::";
                }

                if (!empty($alias["aliasLeft"]["aliasLeftMethod"])) {
                    $tokens[] = $alias["aliasLeft"]["aliasLeftMethod"];
                }

                if (!empty($alias["aliasInsteadOf"])) {
                    $tokens[] = "insteadof";
                }

                if (!empty($alias["aliasAs"])) {
                    $tokens[] = "as";

                    if (!empty($alias["aliasAs"]["visibilityModifiers"])) {
                        $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                            new Ast("", ["visibilityModifiers" => $alias["aliasAs"]["visibilityModifiers"]]),
                            $engine
                        );
                    }
                }

                if (!empty($alias["aliasRight"]["aliasRightClass"])) {
                    $tokens[] = $alias["aliasRight"]["aliasRightClass"];
                }

                if (
                    !empty($alias["aliasRight"]["aliasRightClass"]) &&
                    !empty($alias["aliasRight"]["aliasRightMethod"])
                ) {
                    $tokens[] = "::";
                }

                if (!empty($alias["aliasRight"]["aliasRightMethod"])) {
                    $tokens[] = $alias["aliasRight"]["aliasRightMethod"];
                }

                $tokens[] = ";";
            }

            $tokens[] = "}";
        } else {
            $tokens[] = ";";
        }

        return streamed(aerated($tokens), $engine);
    }
}
