<?php

namespace Pre\Standard\Tests\Expander;

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

    public function test_argument_expansion()
    {
        $expected = '$thing;';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_argument_expansion_with_assignment()
    {
        $expected = '$thing = "param";';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_argument_expansion_with_object_type_and_object_assignment(?string $foo = "bar")
    {
        $expected = <<<CODE
function fn(?\Foo\Bar\Baz \$thing = new \Foo\Bar\Baz("param"))
{
    // noop
}
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_argument_expansion_with_object_type_and_function_assignment()
    {
        $expected = <<<CODE
function fn(?\Foo\Bar\Baz \$thing = \Foo\Bar\baz("param"))
{
    // noop
}
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_argument_expansion_with_function_assignment()
    {
        $expected = '$thing = \Foo\Bar\baz("param");';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
