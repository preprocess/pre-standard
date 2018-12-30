<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ArgumentParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\argument() as alias)
        } >> {
            $(alias ... {
                $(argumentNullable ? {
                    "nullable",
                })

                $(argumentType ? {
                    $$(stringify($(argumentType))),
                })

                $$(stringify($(argumentName))),

                $(argumentValue ? {
                    "equals",

                    $(argumentNew ? {
                        "new",
                    })

                    $$(stringify($(argumentValue)))
                })
            })
        }
    ';

    public function test_basic_argument()
    {
        $code = $this->expand('
            return [
                $thing = "param"
            ];
        ');

        $this->assertEquals(['$thing', 'equals', '"param"'], eval($code));
    }

    public function test_full_argument()
    {
        $code = $this->expand('
            return [
                ? \Foo\Bar\Obj $thing = new \Foo\Bar\Obj("param")
            ];
        ');

        $this->assertEquals(
            ['nullable', '\Foo\Bar\Obj', '$thing', 'equals', 'new', '\Foo\Bar\Obj("param")'],
            eval($code)
        );
    }
}
