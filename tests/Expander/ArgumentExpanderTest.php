<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ArgumentExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\argument())
        } >> {
            $$(\Pre\Standard\Expander\argument($(argument)))
        }
    ';

    public function test_basic_argument()
    {
        $expected = '$thing';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_basic_argument_with_assignment()
    {
        $expected = '$thing = "param"';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_full_argument_with_object()
    {
        $expected = '? \Foo\Bar\Baz $thing = new \Foo\Bar\Baz("param")';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_full_argument_with_function()
    {
        $expected = '? \Foo\Bar\Baz $thing = \Foo\Bar\baz("param")';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
