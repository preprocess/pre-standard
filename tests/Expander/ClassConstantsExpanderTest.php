<?php

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
            $$(\Pre\Standard\Expander\classConstant($(classConstant)))
        }
    ';

    public function test_class_constants_expansion()
    {
        $expected = 'public const FOO = "bar" ; static const BAR = baz("param") ;';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
