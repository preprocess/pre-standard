<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassConstantParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            // https://github.com/marcioAlmada/yay/issues/56
            $(\Pre\Standard\Parser\classConstant() as alias)
        } >> {
            $(alias ... {
                $(classConstantVisibilityModifiers ? {
                    $(classConstantVisibilityModifiers ...(,) {
                        $(classConstantVisibilityModifier ... {
                            $$(stringify($(classConstantVisibilityModifier)))
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
