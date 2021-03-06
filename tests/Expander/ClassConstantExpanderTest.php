<?php

namespace Pre\Standard\Tests\Expander;

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassConstantExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\classConstant())
        } >> {
            $$(\Pre\Standard\Expander\classConstant($(constant)))
        }
    ';

    public function test_class_constant_expansion()
    {
        $expected = <<<CODE
new class {
    public const FOO = "bar";
    static const BAR = baz("param");
};
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
