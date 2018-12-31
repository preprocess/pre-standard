<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassPropertyParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            // https://github.com/marcioAlmada/yay/issues/56
            $(\Pre\Standard\Parser\classProperty() as alias)
        } >> {
            $(alias ... {
                $(classPropertyVisibilityModifiers ? {
                    $(classPropertyVisibilityModifiers ...(,) {
                        $(classPropertyVisibilityModifier ... {
                            $$(stringify($(classPropertyVisibilityModifier)))
                        })
                    }),
                })
    
                $(classPropertyNullableType ? {
                    $(classPropertyNullableType ... {
                        $(classPropertyNullable ? {
                            "nullable",
                        })
    
                        $$(stringify($(classPropertyType))),
                    })
                })
    
                $$(stringify($(classPropertyName))),
    
                $(classPropertyValue ? {
                    $$(stringify($(classPropertyValue))),
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
