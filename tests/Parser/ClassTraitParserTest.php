<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassTraitParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\classTrait())
        } >> {
            $(classTrait ... {
                $(classTraitNames ... {
                    $$(stringify($(classTraitName))),
                })

                $(classTraitBody ? {
                    $(classTraitBody ... {
                        $(classTraitAliases ... {
                            $(classTraitAlias ... {
                                $$(stringify($(classTraitAliasLeft))),

                                $(classTraitAliasInsteadOf ? {
                                    "insteadof",
                                })

                                $(classTraitAliasAs ? {
                                    "as",

                                    $(classTraitAliasAs ... {
                                        $(visibilityModifiers ? {
                                            $(visibilityModifiers ...(,) {
                                                $(visibilityModifier ... {
                                                    $$(stringify($(visibilityModifier)))
                                                })
                                            }),
                                        })
                                    })
                                })

                                $$(stringify($(classTraitAliasRight))),
                            })
                        })
                    })
                })

                $(classTraitBody ! {
                    "no body",
                })
            })
        }
    ';

    public function test_identifies_class_traits()
    {
        $code = $this->expand('
            return [
                [ use \Foo ],
                [ use Foo, Bar, Foo\Bar\Baz ],
                [ use Foo { bar as baz } ],
                [ use Foo { bar as baz; } ],
                [ use Foo { bar as protected baz; } ],
                [ use Foo, Bar { Bar::baz insteadof Foo::baz; } ],
                [ use Foo, Bar { Bar::baz insteadof Foo::baz; Foo::bar as public boo; } ],
            ];
        ');

        $this->assertEquals(
            [
                ['\\Foo', 'no body'],
                ['Foo', 'Bar', 'Foo\\Bar\\Baz', 'no body'],
                ['Foo', 'bar', 'as', 'baz'],
                ['Foo', 'bar', 'as', 'baz'],
                ['Foo', 'bar', 'as', 'protected', 'baz'],
                ['Foo', 'Bar', 'Bar::baz', 'insteadof', 'Foo::baz'],
                ['Foo', 'Bar', 'Bar::baz', 'insteadof', 'Foo::baz', 'Foo::bar', 'as', 'public', 'boo'],
            ],
            eval($code)
        );
    }
}
