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
            $(method ... {
                $(visibilityModifiers ? {
                    $(visibilityModifiers ...(,) {
                        $(visibilityModifier ... {
                            $$(stringify($(visibilityModifier)))
                        })
                    }),
                })
                $(visibilityModifiers ! {
                    "no modifiers",
                })
                $$(stringify($(name))),
                $(arguments ? {
                    $$(stringify($(arguments))),
                })
                $(arguments ! {
                    "no arguments",
                })
                $(returnType ? {
                    $$(stringify($(returnType))),
                })
                $(returnType ! {
                    "no return type",
                })
                $(body ? {
                    $$(stringify($(body))),
                })
                $(body ! {
                    "no body",
                })
            })
        }
    ';

    public function test_identifies_class_methods()
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
