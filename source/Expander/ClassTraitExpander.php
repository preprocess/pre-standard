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

        foreach ($this->find($ast, "classTraitNames") as $classTraitName) {
            $tokens[] = flattened($classTraitName);
            $tokens[] = ",";
        }

        array_pop($tokens);

        if (!empty($branch = $this->find($ast, "classTraitBody"))) {
            $tokens[] = "{";

            foreach ($leaf = $this->find($branch, "classTraitAliases") as $classTraitAlias) {
                $alias = $classTraitAlias["classTraitAlias"];

                if (!empty($alias["classTraitAliasLeft"]["classTraitAliasLeftClass"])) {
                    $tokens[] = $alias["classTraitAliasLeft"]["classTraitAliasLeftClass"];
                }

                if (!empty($alias["classTraitAliasLeft"]["classTraitAliasLeftClass"]) && !empty($alias["classTraitAliasLeft"]["classTraitAliasLeftMethod"])) {
                    $tokens[] = "::";
                }

                if (!empty($alias["classTraitAliasLeft"]["classTraitAliasLeftMethod"])) {
                    $tokens[] = $alias["classTraitAliasLeft"]["classTraitAliasLeftMethod"];
                }

                if (!empty($alias["classTraitAliasInsteadOf"])) {
                    $tokens[] = "insteadof";
                }

                if (!empty($alias["classTraitAliasAs"])) {
                    $tokens[] = "as";

                    if (!empty($alias["classTraitAliasAs"]["visibilityModifiers"])) {
                        $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                            new Ast("", ["visibilityModifiers" => $alias["classTraitAliasAs"]["visibilityModifiers"]]),
                            $engine
                        );
                    }
                }
                
                if (!empty($alias["classTraitAliasRight"]["classTraitAliasRightClass"])) {
                    $tokens[] = $alias["classTraitAliasRight"]["classTraitAliasRightClass"];
                }

                if (!empty($alias["classTraitAliasRight"]["classTraitAliasRightClass"]) && !empty($alias["classTraitAliasRight"]["classTraitAliasRightMethod"])) {
                    $tokens[] = "::";
                }

                if (!empty($alias["classTraitAliasRight"]["classTraitAliasRightMethod"])) {
                    $tokens[] = $alias["classTraitAliasRight"]["classTraitAliasRightMethod"];
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
