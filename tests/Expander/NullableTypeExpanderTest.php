<?php
use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class NullableTypeExpanderTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\nullableType())
        } >> {
            $$(\Pre\Standard\Expander\nullableType($(nullableType)))
        }
    ';

    public function test_nullable_type_expansion()
    {
        $expected = <<<CODE
function fn(?string \$one, bool \$two, ?int \$three)
{
    // noop
}
CODE;

        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}
