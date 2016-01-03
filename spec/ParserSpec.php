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
        $examples = $this->commonIniExamples();

        $examples[] = [
            [
                'section' => [
                    'environment' => 'ENV="value"'
                ],
            ],
            "[section]\nenvironment = ENV=\"value\"\n\n",
        ];

        return $examples;
    }
}
