<?php

use PHPUnit\Framework\TestCase;
use Pre\Standard\Tests\HasExpand;
use Yay\Engine;

class ArgumentsParserTest extends TestCase
{
    use HasExpand;

    protected $macro = '
        $(macro) {
            $(\Pre\Standard\Parser\arguments())
        } >> {
            $(arguments ...(,) {

                // ...not really interested in re-testing the argument
                // parser so this is just to make sure arguments() is
                // breaking apart a list of arguments correctly

                $$(stringify(
                    $(argument)
                ))
            })
        }
    ';

    public function test_identifies_argument_lists()
    {
        $code = $this->expand('
            return [
                $one = "one", string $two = "two"
            ];
        ');

        $items = eval($code);

        $expected = ['$one="one"', 'string$two="two"'];

        $actual = array_map(function ($item) {
            return trim($item);
        }, $items);

        $this->assertEquals($expected, $actual);
    }
}
