<?php

namespace spec\Indigo\Ini;

use PhpSpec\ObjectBehavior;

class ParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Ini\Parser');
    }

    function it_parses_plain_ini()
    {
        $parsedIni = [
            'section' => [
                'key' => 'value',
            ],
        ];

        $ini = <<< EOF
[section]
key = "value"

EOF;

        $this->parse($ini)->shouldReturn($parsedIni);
    }

    function it_parses_multiple_sections()
    {
        $parsedIni = [
            'section' => [
                'key' => 'value',
            ],
            'section2' => [
                'key' => 'value',
            ],
        ];

        $ini = <<< EOF
[section]
key = "value"

[section2]
key = "value"

EOF;

        $this->parse($ini)->shouldReturn($parsedIni);
    }

    function it_parses_complex_sections()
    {
        $parsedIni = [
            'section1' => [
                'section1' => [
                    'value1',
                    'value2',
                    null,
                ],
                'key1' => 'value3',
            ],
            'section2' => [
                'key1' => true,
                'key2' => false,
                'key3' => null,
            ],
        ];

        $ini = <<< EOF
[section1]
section1[] = "value1"
section1[] = "value2"
section1[] = null
key1 = "value3"

[section2]
key1 = true
key2 = false
key3 = null

EOF;
        $this->parse($ini)->shouldReturn($parsedIni);
    }

    function it_throws_an_exception_when_non_string_ini_is_passed()
    {
        $this->shouldThrow('Indigo\Ini\Exception\ParserException')->duringParse(null);
    }

    function it_parses_ini_with_empty_keys()
    {
        $parsedIni = [
            'section' => [
                'section' => [
                    'section_value',
                    'another_section_value',
                ],
                'key' => 'value',
            ],
        ];

        $ini = <<< EOF
[section]
section[] = "section_value"
section[] = "another_section_value"
key = "value"

EOF;

        $this->parse($ini)->shouldReturn($parsedIni);
    }

    function it_parses_ini_with_array_values()
    {
        $parsedIni = [
            'section' => [
                'key' => [
                    'value',
                    'value',
                ],
            ],
        ];

        $ini = <<< EOF
[section]
key[] = "value"
key[] = "value"

EOF;

        $this->parse($ini)->shouldReturn($parsedIni);
    }

    function it_parses_ini_with_boolean_values()
    {
        $parsedIni = [
            'section' => [
                'key' => true,
                'key2' => false,
                'key3' => true,
                'key4' => false,
                'key5' => true,
                'key6' => false,
            ],
        ];

        $ini = <<< EOF
[section]
key = true
key2 = false
key3 = On
key4 = Off
key5 = Yes
key6 = No

EOF;

        $this->parse($ini)->shouldReturn($parsedIni);
    }

    function it_parses_ini_with_non_string_boolean_values()
    {
        $parsedIni = [
            'section' => [
                'key' => true,
                'key2' => false,
                'key3' => true,
                'key4' => false,
                'key5' => true,
                'key6' => false,
            ],
        ];

        $ini = <<< EOF
[section]
key = true
key2 = false
key3 = On
key4 = Off
key5 = Yes
key6 = No

EOF;

        $this->parse($ini)->shouldReturn($parsedIni);
    }

    function it_parses_ini_with_funky_boolean_values()
    {
        $parsedIni = [
            'section' => [
                'key' => true,
                'key2' => false,
                'key3' => true,
                'key4' => false,
                'key5' => true,
                'key6' => false,
            ],
        ];

        $ini = <<< EOF
[section]
key = tRuE
key2 = fAlSe
key3 = oN
key4 = oFf
key5 = yEs
key6 = nO

EOF;

        $this->parse($ini)->shouldReturn($parsedIni);
    }

    function it_parses_ini_with_null_value()
    {
        $parsedIni = [
            'section' => [
                'key' => null,
            ],
        ];

        $ini = <<< EOF
[section]
key = null

EOF;

        $this->parse($ini)->shouldReturn($parsedIni);
    }

    function it_parses_ini_with_numeric_values()
    {
        $parsedIni = [
            'section' => [
                'key' => 1,
                'key2' => -1,
                'key3' => 1.2,
                'key4' => '-1.2',
                'key5' => 0,
            ],
        ];

        $ini = <<< EOF
[section]
key = 1
key2 = -1
key3 = 1.2
key4 = -1.2
key5 = 0

EOF;

        $this->parse($ini)->shouldReturn($parsedIni);
    }
}
