<?php

namespace spec\Indigo\Ini;

trait IniExamples
{
    public function iniExamples()
    {
        return [
            [
                [
                    'section' => [
                        'key' => 'value',
                    ],
                ],
                "[section]\nkey = value\n",
            ],
            [
                [
                    'section' => [
                        'key' => 'value',
                    ],
                    'section2' => [
                        'key' => 'value',
                    ],
                ],
                "[section]\nkey = value\n\n[section2]\nkey = value\n",
            ],
            [
                [
                    'section1' => [
                        'section1' => [
                            'value1',
                            'value2',
                        ],
                        'key1' => 'value3',
                    ],
                    'section2' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
                ],
                "[section1]\nsection1[] = value1\nsection1[] = value2\nkey1 = value3\n\n[section2]\nkey1 = value1\nkey2 = value2\n",
            ],
            [
                [
                    'section' => [
                        'section' => [
                            'section_value',
                            'another_section_value',
                        ],
                        'key' => 'value',
                    ],
                ],
                "[section]\nsection[] = section_value\nsection[] = another_section_value\nkey = value\n",
            ],
            [
                [
                    'section' => [
                        'key' => [
                            'value',
                            'value',
                        ],
                    ],
                ],
                "[section]\nkey[] = value\nkey[] = value\n",
            ],
            [
                [
                    'section' => [
                        'key' => null,
                    ],
                ],
                "[section]\nkey = null\n",
            ],
            [
                [
                    'section' => [
                        'key' => 1,
                        'key2' => -1,
                        'key3' => 1.2,
                        'key4' => -1.2,
                        'key5' => 0,
                    ],
                ],
                "[section]\nkey = 1\nkey2 = -1\nkey3 = 1.2\nkey4 = -1.2\nkey5 = 0\n",
            ],
        ];
    }
}
