<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class VisibilityModifiersParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\visibilityModifiers())
        } >> {
            $$(stringify($(visibilityModifiers)))
        }
    ';

    public function test_identifies_visibility_modifiers()
    {
        $code = $this->expand('
            return [
                abstract,
                public,
                protected,
                private,
                static,
            ];
        ');

        $this->assertEquals(['abstract', 'public', 'protected', 'private', 'static'], eval($code));
    }
}
