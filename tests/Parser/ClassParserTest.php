<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\cls())
        } >> {
            $(cls ... {
                [

                    $(classNamed ? {
                        "named class",

                        $(classNamed ... {
                            $$(stringify($(className))),
                        })
                    })

                    $(classAnonymous ? {
                        "anonymous class",

                        $(classAnonymous ... {
                            $(classArguments ? {
                                "arguments",
                                $$(stringify($(classArguments))),
                            })

                            $(classArguments ! {
                                "no arguments",
                            })
                        })
                    })

                    $(classModifiers ? {
                        "modifiers",
                        $(classModifiers ... {        
                            $(classExtendsType ? {
                                "extends",
                                $$(stringify($(classExtendsType))),
                            })

                            $(classImplementsTypes ? {
                                "implements",
                                $(classImplementsTypes ... {
                                    $$(stringify($(classImplementsType))),
                                })
                            })
                        })
                    })

                    $(classModifiers ! {
                        "no modifiers",
                    })

                    $(classMembers ? {
                        "members"

                        $(classMembers ... {
                            // $(classTrait ? {
                            //     $$(\Pre\Standard\Expander\classTrait($(classTrait)))
                            // })

                            // $(classConstant ? {
                            //     $$(\Pre\Standard\Expander\classConstant($(classConstant)))
                            // })

                            // $(classProperty ? {
                            //     $$(\Pre\Standard\Expander\classProperty($(classProperty)))
                            // })

                            // $(classMethod ? {
                            //     $$(\Pre\Standard\Expander\classMethod($(classMethod)))
                            // })
                        })
                    })

                    $(classMembers ! {
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
                    public function works($value): bool {
                        return $value;
                    }
                }
            ]
        ');

        // print_r($code);
        // exit;

        $this->assertEquals([
            ["anonymous class", "no arguments", "no modifiers", "no members"],
            ["anonymous class", "arguments", "1, 2, 3", "no modifiers", "no members"],
            ["named class", "Example", "no modifiers", "no members"],
            ["named class", "Example", "modifiers", "extends", "Base", "no members"],
            ["named class", "Example", "modifiers", "implements", "One", "Two", "no members"],
            ["named class", "Example", "modifiers", "extends", "Base", "implements", "One", "Two", "no members"],
            ["named class", "ExampleHasTrait", "no modifiers", "members"],
            ["named class", "ExampleHasConstant", "no modifiers", "members"],
            ["named class", "ExampleHasProperty", "no modifiers", "members"],
            ["named class", "ExampleHasMethod", "no modifiers", "members"],
        ], eval($code));
    }
}
