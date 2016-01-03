<?php

namespace spec\Indigo\Ini;

use Indigo\Ini\Renderer;
use PhpSpec\ObjectBehavior;

class RendererSpec extends ObjectBehavior
{
    use IniExamples {
        iniExamples as commonIniExamples;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Ini\Renderer');
    }

    /**
     *  @dataProvider iniExamples
     */
    function it_renders_ini($iniArray, $iniString)
    {
        $this->render($iniArray)->shouldReturn($iniString);
    }

    function it_throws_an_exception_when_non_array_section_is_passed()
    {
        $ini = [
            'section' => 'invalid',
        ];

        $this->shouldThrow('Indigo\Ini\Exception\RendererException')->duringRender($ini);
    }

    function it_throws_an_exception_when_multi_dimensional_array_is_passed()
    {
        $ini = [
            'section' => [
                'key' => [
                    ['value'],
                    'value',
                ],
            ],
        ];

        $this->shouldThrow('Indigo\Ini\Exception\RendererException')->duringRender($ini);
    }

    function it_renders_ini_with_array_values_in_concat_mode()
    {
        $this->beConstructedWith(Renderer::ARRAY_MODE_CONCAT);

        $ini = [
            'section' => [
                'key' => [
                    'value',
                    'value',
                ],
            ],
        ];

        $renderedIni = <<< EOF
[section]
key = "value,value"

EOF;

        $this->render($ini)->shouldReturn($renderedIni);
    }

    function it_renders_ini_with_multi_dimensional_array_values_in_concat_mode()
    {
        $this->beConstructedWith(Renderer::ARRAY_MODE_CONCAT);

        $ini = [
            'section' => [
                'key' => [
                    ['value'],
                    'value',
                ],
            ],
        ];

        $renderedIni = <<< EOF
[section]
key = "value,value"

EOF;

        $this->render($ini)->shouldReturn($renderedIni);
    }

    function it_renders_ini_with_section_array_values_in_concat_mode()
    {
        $this->beConstructedWith(Renderer::ARRAY_MODE_CONCAT);

        $ini = [
            'section' => [
                ['value', 'another_value'],
                'value',
                'key' => [
                    'value',
                ],
            ],
        ];

        $renderedIni = <<< EOF
[section]
section = "value,another_value,value"
key = "value"

EOF;

        $this->render($ini)->shouldReturn($renderedIni);
    }

    function it_renders_ini_with_boolean_values()
    {
        $ini = [
            'section' => [
                'key' => true,
                'key2' => false,
            ],
        ];

        $renderedIni = <<< EOF
[section]
key = 1
key2 = 0

EOF;

        $this->render($ini)->shouldReturn($renderedIni);
    }

    function it_renders_ini_with_boolean_values_in_boolean_string_mode()
    {
        $this->beConstructedWith(Renderer::ARRAY_MODE_ARRAY, Renderer::BOOLEAN_MODE_BOOL_STRING);

        $ini = [
            'section' => [
                'key' => true,
                'key2' => false,
            ],
        ];

        $renderedIni = <<< EOF
[section]
key = true
key2 = false

EOF;

        $this->render($ini)->shouldReturn($renderedIni);
    }

    function it_renders_ini_with_boolean_values_in_string_mode()
    {
        $this->beConstructedWith(Renderer::ARRAY_MODE_ARRAY, Renderer::BOOLEAN_MODE_STRING);

        $ini = [
            'section' => [
                'key' => true,
                'key2' => false,
            ],
        ];

        $renderedIni = <<< EOF
[section]
key = On
key2 = Off

EOF;

        $this->render($ini)->shouldReturn($renderedIni);
    }

    public function iniExamples()
    {
        $examples = [
            [
                [
                    'section' => [
                        'key' => true,
                        'key2' => false,
                    ],
                ],
                "[section]\nkey = 1\nkey2 = 0\n",
            ],
        ];

        return array_merge($examples, $this->commonIniExamples());
    }
}
