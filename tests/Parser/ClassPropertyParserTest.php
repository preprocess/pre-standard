<?php

namespace Pre\Standard\Tests\Parser;

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassPropertyParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\classProperty())
        } >> {
            $(property ... {
                $(visibilityModifiers ? {
                    $(visibilityModifiers ...(,) {
                        $(visibilityModifier ... {
                            $$(stringify($(visibilityModifier)))
                        })
                    }),
                })
    
                $(nullableType ? {
                    $(nullableType ... {
                        $(nullable ? {
                            "nullable",
                        })
    
                        $$(stringify($(type))),
                    })
                })
    
                $$(stringify($(name))),
    
                $(value ? {
                    $$(stringify($(value))),
                })
            })
        }
    ';

    public function test_identifies_class_properties()
    {
        $code = $this->expand('
            return [
                [ public $foo = "bar"; ],
                [ static ?string $bar = baz("param"); ],
            ];
        ');

        $this->assertEquals(
            [['public', '$foo', '"bar"'], ['static', 'nullable', 'string', '$bar', 'baz("param")']],
            eval($code)
        );
    }
}
