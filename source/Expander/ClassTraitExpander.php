<?php

namespace Pre\Standard\Expander;

use function join;

use Pre\Standard\AbstractExpander;
use function Pre\Standard\Internal\aerated;
use function Pre\Standard\Internal\flattened;
use function Pre\Standard\Internal\named;
use function Pre\Standard\Internal\streamed;

use Yay\Engine;
use Yay\TokenStream;

class ClassTraitExpander extends AbstractExpander
{
    public function expand($source, Engine $engine, string $prefix = null): TokenStream
    {
        $tokens = ["use"];
        $source = $this->resolve($source);

        foreach ($this->find($source, named("classTraitNames", $prefix)) as $classTraitName) {
            $tokens[] = flattened($classTraitName);
            $tokens[] = ",";
        }

        array_pop($tokens);

        if (!empty($branch = $this->find($source, named("classTraitBody", $prefix)))) {
            $tokens[] = "{";

            foreach ($leaf = $this->find($branch, named("classTraitAliases", $prefix)) as $classTraitAlias) {
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

                    if (!empty($alias["classTraitAliasAs"]["classTraitAliasVisibilityModifiers"])) {
                        $tokens[] = (string) (new VisibilityModifiersExpander())->expand(
                            [named("classTraitAliasVisibilityModifiers", $prefix) => $alias["classTraitAliasAs"]["classTraitAliasVisibilityModifiers"]],
                            $engine,
                            named("classTraitAlias", $prefix),
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
