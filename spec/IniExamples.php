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
                "[section]\nkey = value\n\n",
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
                "[section]\nkey = value\n\n[section2]\nkey = value\n\n",
            ],
            [
                [
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
                ],
                "[section1]\nsection1[] = value1\nsection1[] = value2\nsection1[] = null\nkey1 = value3\n\n[section2]\nkey1 = true\nkey2 = false\nkey3 = null\n\n",
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
                "[section]\nsection[] = section_value\nsection[] = another_section_value\nkey = value\n\n",
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
                "[section]\nkey[] = value\nkey[] = value\n\n",
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
                "[section]\nkey = true\nkey2 = false\nkey3 = On\nkey4 = Off\nkey5 = Yes\nkey6 = No\n\n",
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
                "[section]\nkey = true\nkey2 = false\nkey3 = On\nkey4 = Off\nkey5 = Yes\nkey6 = No\n\n",
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
                "[section]\nkey = tRuE\nkey2 = fAlSe\nkey3 = oN\nkey4 = oFf\nkey5 = yEs\nkey6 = nO\n\n"
            ],
            [
                [
                    'section' => [
                        'key' => null,
                    ],
                ],
                "[section]\nkey = null\n\n",
            ],
            [
                [
                    'section' => [
                        'key' => 1,
                        'key2' => -1,
                        'key3' => 1.2,
                        'key4' => -1.2,
                        'key5' => 0,
                        'key6' => 22,
                    ],
                ],
                "[section]\nkey = 1\nkey2 = -1\nkey3 = 1.2\nkey4 = -1.2\nkey5 = 0\nkey6 = 022\n\n",
            ],
        ];
    }
}
