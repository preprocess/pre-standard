<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class NullableTypeTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            // https://github.com/marcioAlmada/yay/issues/56
            $(\Pre\Standard\Parser\nullableType() as alias)
        } >> {
            $(alias ... {
                $(nullable ? {
                    "nullable",
                })

                $$(stringify($(type))),
            })
        }
    ';

    public function test_nullable_types()
    {
        $code = $this->expand('
            return [
                [ ? string ],
                [ int ],
            ];
        ');

        $this->assertEquals([["nullable", "string"], ["int"]], eval($code));
    }
}
