<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class VisibilityModifiersExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\visibilityModifiers())
        } >> {
            $$(\Pre\Standard\Expander\visibilityModifiers($(visibilityModifiers)))
        }
    ';

    public function test_visibility_modifiers_expansion()
    {
        $expected = 'public protected private static';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
