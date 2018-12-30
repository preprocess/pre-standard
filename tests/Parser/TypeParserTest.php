<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class TypeParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            // https://github.com/marcioAlmada/yay/issues/56
            $(\Pre\Standard\Parser\type() as alias)
        } >> {
            $$(stringify($(alias)))
        }
    ';

    public function test_identifies_classes()
    {
        $code = $this->expand('
            return [
                \Foo\Bar\Baz
            ];
        ');

        $this->assertEquals(['\Foo\Bar\Baz'], eval($code));
    }

    public function test_identifies_scalars()
    {
        $code = $this->expand('
            return [
                array,
                string,
                bool,
                boolean,
                number,
                int,
                integer,
                float,
                null,
                resource,
                object,
                iterable,
                callable,
                callback,
                void,
                mixed,
            ];
        ');

        $this->assertEquals(
            [
                'array',
                'string',
                'bool',
                'boolean',
                'number',
                'int',
                'integer',
                'float',
                'null',
                'resource',
                'object',
                'iterable',
                'callable',
                'callback',
                'void',
                'mixed',
            ],
            eval($code)
        );
    }
}
