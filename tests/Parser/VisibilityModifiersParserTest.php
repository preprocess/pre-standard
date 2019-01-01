<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class VisibilityModifiersParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            // https://github.com/marcioAlmada/yay/issues/56
            $(\Pre\Standard\Parser\visibilityModifiers() as alias)
        } >> {
            $$(stringify($(alias)))
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
