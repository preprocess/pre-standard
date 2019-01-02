<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ClassTraitExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\classTrait())
        } >> {
            $$(\Pre\Standard\Expander\classTrait($(classTrait)))
        }
    ';

    public function test_class_trait_expansion()
    {
        $expected = <<<CODE
new class {
    use \Foo;

    use Foo, Bar, Foo\Bar\Baz;

    use Foo {
        bar as baz;
    }

    use Foo {
        bar as baz;
    }

    use Foo {
        bar as protected baz;
    }

    use Foo, Bar {
        Bar::baz insteadof Foo;
    }

    use Foo, Bar {
        Bar::baz insteadof Foo;
        Foo::bar as public boo;
    }
};
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
