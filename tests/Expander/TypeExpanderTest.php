a<?php
use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class TypeExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\type())
        } >> {
            $$(\Pre\Standard\Expander\type($(type)))
        }
    ';

    public function test_type_expansion()
    {
        $expected = 'string $one, bool $two, int $three';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}

