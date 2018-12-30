<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ArgumentsExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\arguments())
        } >> {
            $$(\Pre\Standard\Expander\arguments($(arguments)))
        }
    ';

    public function test_basic_arguments()
    {
        $expected = '$one = "one", string $two = "two", $three = three("three")';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
