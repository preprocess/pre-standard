<?php
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
        $expected = <<<CODE
function fn(string \$one, bool \$two, int \$three)
{
    // noop
}
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
