<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\clas())
        } >> {
            $(clas ... {
                [

                    $(named ? {
                        "named class",

                        $(named ... {
                            $$(stringify($(name))),
                        })
                    })

                    $(anonymous ? {
                        "anonymous class",

                        $(anonymous ... {
                            $(arguments ? {
                                "arguments",
                                $$(stringify($(arguments))),
                            })

                            $(arguments ! {
                                "no arguments",
                            })
                        })
                    })

                    $(modifiers ? {
                        "modifiers",
                        $(modifiers ... {        
                            $(extendsType ? {
                                "extends",
                                $$(stringify($(extendsType))),
                            })

                            $(implementsTypes ? {
                                "implements",
                                $(implementsTypes ... {
                                    $$(stringify($(implementsType))),
                                })
                            })
                        })
                    })

                    $(modifiers ! {
                        "no modifiers",
                    })

                    $(members ? {
                        "members",

                        $(members ... {
                            $(trait ? {
                                $$(stringify($$(\Pre\Standard\Expander\classTrait($(trait))))),
                            })

                            $(constant ? {
                                $$(stringify($$(\Pre\Standard\Expander\classConstant($(constant))))),
                            })

                            $(property ? {
                                $$(stringify($$(\Pre\Standard\Expander\classProperty($(property))))),
                            })

                            $(method ? {
                                $$(stringify($$(\Pre\Standard\Expander\classMethod($(method))))),
                            })
                        })
                    })

                    $(members ! {
                        "no members"
                    })

                ],
            })
        }
    ';

    public function test_identifies_classes()
    {
        $code = $this->expand('
            return [
                new class() {}
                new class(1, 2, 3) {}
                class Example {}
                class Example extends Base {}
                class Example implements One, Two {}
                class Example extends Base implements One, Two {}

                class ExampleHasTrait {
                    use TestTrait;
                }

                class ExampleHasConstant {
                    const FOO = "bar";
                }

                class ExampleHasProperty {
                    private $property = "value";
                }

                class ExampleHasMethod {
                    public function works($value): bool { return $value; }
                }
            ]
        ');

        $this->assertEquals(
            [
                ["anonymous class", "no arguments", "no modifiers", "no members"],
                ["anonymous class", "arguments", "123", "no modifiers", "no members"],
                ["named class", "Example", "no modifiers", "no members"],
                ["named class", "Example", "modifiers", "extends", "Base", "no members"],
                ["named class", "Example", "modifiers", "implements", "One", "Two", "no members"],
                ["named class", "Example", "modifiers", "extends", "Base", "implements", "One", "Two", "no members"],
                ["named class", "ExampleHasTrait", "no modifiers", "members", "use TestTrait ;"],
                ["named class", "ExampleHasConstant", "no modifiers", "members", 'const FOO = "bar" ;'],
                ["named class", "ExampleHasProperty", "no modifiers", "members", 'private $property = "value" ;'],
                [
                    "named class",
                    "ExampleHasMethod",
                    "no modifiers",
                    "members",
                    'public function works ( $value ) : bool { return $value;  }',
                ],
            ],
            eval($code)
        );
    }
}
