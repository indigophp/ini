<?php

namespace spec\Indigo\Ini;

use PhpSpec\ObjectBehavior;

class ParserSpec extends ObjectBehavior
{
    use IniExamples {
        iniExamples as commonIniExamples;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Ini\Parser');
    }

    /**
     *  @dataProvider iniExamples
     */
    function it_parses_ini($iniArray, $iniString)
    {
        $this->parse($iniString)->shouldReturn($iniArray);
    }

    function it_throws_an_exception_when_non_string_ini_is_passed()
    {
        $this->shouldThrow('Indigo\Ini\Exception\ParserException')->duringParse(null);
    }

    public function iniExamples()
    {
        $examples = [
            [
                [
                    'section' => [
                        'key' => true,
                        'key2' => false,
                        'key3' => true,
                        'key4' => false,
                        'key5' => true,
                        'key6' => false,
                    ],
                ],
                "[section]\nkey = true\nkey2 = false\nkey3 = On\nkey4 = Off\nkey5 = Yes\nkey6 = No\n",
            ],
            [
                [
                    'section' => [
                        'key' => true,
                        'key2' => false,
                        'key3' => true,
                        'key4' => false,
                        'key5' => true,
                        'key6' => false,
                    ],
                ],
                "[section]\nkey = \"true\"\nkey2 = \"false\"\nkey3 = \"On\"\nkey4 = \"Off\"\nkey5 = \"Yes\"\nkey6 = \"No\"\n",
            ],
            [
                [
                    'section' => [
                        'key' => true,
                        'key2' => false,
                        'key3' => true,
                        'key4' => false,
                        'key5' => true,
                        'key6' => false,
                    ],
                ],
                "[section]\nkey = tRuE\nkey2 = fAlSe\nkey3 = oN\nkey4 = oFf\nkey5 = yEs\nkey6 = nO\n",
            ],
            [
                [
                    'section' => [
                        'environment' => 'ENV="value"'
                    ],
                ],
                "[section]\nenvironment = ENV=\"value\"\n\n",
            ],
            [
                [
                    'section' => [
                        'key1' => 22,
                    ],
                ],
                "[section]\nkey1 = 022\n",
            ],
        ];

        return array_merge($examples, $this->commonIniExamples());
    }
}
