<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class NullableTypeParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\nullableType())
        } >> {
            $(nullableType ... {
                $(nullable ? {
                    "nullable",
                })

                $$(stringify($(type))),
            })
        }
    ';

    public function test_identifies_nullable_types()
    {
        $code = $this->expand('
            return [
                [ ? string ],
                [ int ],
            ];
        ');

        $this->assertEquals([['nullable', 'string'], ['int']], eval($code));
    }
}
