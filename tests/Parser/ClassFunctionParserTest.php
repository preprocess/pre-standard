<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassFunctionParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            // https://github.com/marcioAlmada/yay/issues/56
            $(\Pre\Standard\Parser\classFunction() as alias)
        } >> {
            $(alias ... {
                $(classFunctionVisibilityModifiers ? {
                    $(classFunctionVisibilityModifiers ...(,) {
                        $(classFunctionVisibilityModifier ... {
                            $$(stringify($(classFunctionVisibilityModifier)))
                        })
                    }),
                })
                $(classFunctionVisibilityModifiers ! {
                    "no modifiers",
                })
                $$(stringify($(classFunctionName))),
                $(classFunctionArguments ? {
                    $$(stringify($(classFunctionArguments))),
                })
                $(classFunctionArguments ! {
                    "no arguments",
                })
                $(classFunctionReturnType ? {
                    $$(stringify($(classFunctionReturnType))),
                })
                $(classFunctionReturnType ! {
                    "no return type",
                })
                $(classFunctionBody ? {
                    $$(stringify($(classFunctionBody))),
                })
                $(classFunctionBody ! {
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
