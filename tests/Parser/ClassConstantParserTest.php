<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassConstantParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\classConstant())
        } >> {
            $(classConstant ... {
                $(visibilityModifiers ? {
                    $(visibilityModifiers ...(,) {
                        $(visibilityModifier ... {
                            $$(stringify($(visibilityModifier)))
                        })
                    }),
                })
                $$(stringify($(classConstantName))),
                $$(stringify($(classConstantValue))),
            })
        }
    ';

    public function test_identifies_class_constants()
    {
        $code = $this->expand('
            return [
                [ public const FOO = "bar"; ],
                [ static const BAR = baz("param"); ],
            ];
        ');

        $this->assertEquals([['public', 'FOO', '"bar"'], ['static', 'BAR', 'baz("param")']], eval($code));
    }
}
