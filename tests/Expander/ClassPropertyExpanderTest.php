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

    public function test_class_property_expansion()
    {
        // we get a warning because prettier-php
        // doesn't recognise nullable type properties

        $expected = <<<CODE
new class {
    public \$foo = "bar" ;
    static ? string \$bar = baz("param") ;
};
CODE;
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
