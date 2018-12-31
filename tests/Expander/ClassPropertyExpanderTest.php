<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassPropertyExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\classProperty())
        } >> {
            $$(\Pre\Standard\Expander\classProperty($(classProperty)))
        }
    ';

    public function test_class_constants_expansion()
    {
        $expected = 'public $foo = "bar" ; static ? string $bar = baz("param") ;';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
