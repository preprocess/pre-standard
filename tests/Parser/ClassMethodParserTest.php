<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassMethodParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\classMethod())
        } >> {
            $(classMethod ... {
                $(classMethodVisibilityModifiers ? {
                    $(classMethodVisibilityModifiers ...(,) {
                        $(classMethodVisibilityModifier ... {
                            $$(stringify($(classMethodVisibilityModifier)))
                        })
                    }),
                })
                $(classMethodVisibilityModifiers ! {
                    "no modifiers",
                })
                $$(stringify($(classMethodName))),
                $(classMethodArguments ? {
                    $$(stringify($(classMethodArguments))),
                })
                $(classMethodArguments ! {
                    "no arguments",
                })
                $(classMethodReturnType ? {
                    $$(stringify($(classMethodReturnType))),
                })
                $(classMethodReturnType ! {
                    "no return type",
                })
                $(classMethodBody ? {
                    $$(stringify($(classMethodBody))),
                })
                $(classMethodBody ! {
                    "no body",
                })
            })
        }
    ';

    public function test_identifies_class_constants()
    {
        $code = $this->expand('
            return [
                [ public function greet($name = "world") { print "hello" . $name; } ],
                [ private static function instance() { if (!static::$instance) static::$instance = new static(); return static::$instance; } ],
                [ function noop(): void {} ],
            ];
        ');

        $this->assertEquals(
            [
                ['public', 'greet', '$name="world"', 'no return type', 'print "hello" . $name; '],
                [
                    'private',
                    'static',
                    'instance',
                    'no arguments',
                    'no return type',
                    'if (!static::$instance) static::$instance = new static(); return static::$instance; ',
                ],
                ['no modifiers', 'noop', 'no arguments', ':void', 'no body'],
            ],
            eval($code)
        );
    }
}
