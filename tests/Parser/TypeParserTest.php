<?php

namespace Pre\Standard\Tests\Parser;

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class TypeParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\type())
        } >> {
            $$(stringify($(type)))
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

    public function test_identifies_scalar_types()
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
