<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class VisibilityModifiersExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\visibilityModifiers()) as visibilityModifiers
        } >> {
            $$(\Pre\Standard\Expander\visibilityModifiers($(visibilityModifiers)))
        }
    ';

    public function test_visibility_modifiers_expansion()
    {
        $expected = <<<CODE
new class {
    abstract public function fn1();

    protected function fn1()
    {
        // noop
    }

    private function fn2()
    {
        // noop
    }

    static function fn3()
    {
        // noop
    }
};
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
