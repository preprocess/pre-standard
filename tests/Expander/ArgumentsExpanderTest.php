<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ArgumentsExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\arguments()) as arguments
        } >> {
            $$(\Pre\Standard\Expander\arguments($(arguments)))
        }
    ';

    public function test_argument_list_expansion()
    {
        $expected = <<<CODE
function fn(\$one = "one", string \$two = "two", \$three = three("three"))
{
    // noop
}
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
