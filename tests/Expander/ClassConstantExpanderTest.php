<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassConstantExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\classConstant()) as classConstant
        } >> {
            $$(\Pre\Standard\Expander\classConstant($(classConstant)))
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
