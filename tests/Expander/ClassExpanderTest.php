<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\clas())
        } >> {
            $$(\Pre\Standard\Expander\clas($(clas)))
        }
    ';

    public function test_anonymous_class_expansion()
    {
        $expected = <<<CODE
new class(\$one, "two") {
    public function works()
    {
        return "foo";
    }
    use Thing;
    const FOO = "bar";
    protected \$bar = "baz";
};
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_named_class_expansion()
    {
        $expected = <<<CODE
class Foo
{
    public function works()
    {
        return "foo";
    }
}
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
