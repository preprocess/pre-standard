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
            $(class ... {
                $$(stringify($(className))) ,

                $(classModifiers ? {
                    $(classModifiers ... {
                        $(classExtends ? {
                            "extends" ,

                            $(classExtends ... {
                                $$(stringify($(classExtendsType))) ,
                            })
                        })

                        $(classImplements ? {
                            "implements" ,

                            $(classImplements ... {
                                $(classImplementsTypes ... {
                                    $$(stringify($(classImplementsType))) ,    
                                })
                            })
                        })
                    })
                })

                $(classMembers ? {
                    $(classMembers ... {
                        "member" ,

                        $(classConstant ? {
                            $$(\Pre\Standard\Expander\classConstant($(classConstant))) ,
                        })
                    })
                })
            })
        }
    ';

    public function test_identifies_class_constants()
    {
        $code = $this->expand('
            [
                class Example extends Base implements FirstInterface, SecondInterface {
                    const FOO = "FOO";
                }
            ]
        ');

        print_r($code);
        exit;

        // $this->assertEquals([['public', 'FOO', '"bar"'], ['static', 'BAR', 'baz("param")']], eval($code));
    }
}
