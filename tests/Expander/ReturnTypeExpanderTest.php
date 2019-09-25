<?php

namespace Pre\Standard\Tests\Expander;

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ReturnTypeExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\returnType())
        } >> {
            $$(\Pre\Standard\Expander\returnType($(returnType)))
        }
    ';

    public function test_return_type_extension_with_type()
    {
        $expected = <<<CODE
function fnWithReturnType(): string
{
    // noop
}
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_return_type_extension_without_type()
    {
        $expected = <<<CODE
function fnWithReturnType()
{
    // noop
}
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
