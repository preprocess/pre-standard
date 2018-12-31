a<?php
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
        $expected = '?string $one, bool $two, ? int $three';
        $actual = $this->expand($expected);

        $this->assertEquals($expected, $actual);
    }
}

