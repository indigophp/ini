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

    /**
     *  @dataProvider iniExamplesWithMode
     */
    function it_renders_ini_with_mode($mode, $iniArray, $iniString)
    {
        $this->beConstructedWith($mode);

        $this->render($iniArray)->shouldReturn($iniString);
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

    public function iniExamplesWithMode()
    {
        return [
            [
                Renderer::ARRAY_MODE_CONCAT,
                [
                    'section' => [
                        'key' => [
                            'value',
                            'value',
                        ],
                    ],
                ],
                "[section]\nkey = value,value\n",
            ],
            [
                Renderer::ARRAY_MODE_CONCAT,
                [
                    'section' => [
                        'key' => [
                            ['value'],
                            'value',
                        ],
                    ],
                ],
                "[section]\nkey = value,value\n",
            ],
            [
                Renderer::ARRAY_MODE_CONCAT,
                [
                    'section' => [
                        ['value', 'another_value'],
                        'value',
                        'key' => [
                            'value',
                        ],
                    ],
                ],
                "[section]\nsection = value,another_value,value\nkey = value\n",
            ],
            [
                Renderer::BOOLEAN_MODE_BOOL_STRING,
                [
                    'section' => [
                        'key' => true,
                        'key2' => false,
                    ],
                ],
                "[section]\nkey = true\nkey2 = false\n",
            ],
            [
                Renderer::BOOLEAN_MODE_STRING,
                [
                    'section' => [
                        'key' => true,
                        'key2' => false,
                    ],
                ],
                "[section]\nkey = On\nkey2 = Off\n",
            ],
            [
                Renderer::STRING_MODE_QUOTE | Renderer::ARRAY_MODE_CONCAT,
                [
                    'section' => [
                        'key' => ['value1', 'value2'],
                        'key2' => 'value3',
                    ],
                ],
                "[section]\nkey = \"value1,value2\"\nkey2 = \"value3\"\n",
            ],
        ];
    }
}
