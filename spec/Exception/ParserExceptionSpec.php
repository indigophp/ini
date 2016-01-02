<?php

namespace spec\Indigo\Ini\Exception;

use PhpSpec\ObjectBehavior;

class ParserExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Ini\Exception\ParserException');
    }

    function it_is_a_runtime_exception()
    {
        $this->shouldHaveType('RuntimeException');
    }
}
