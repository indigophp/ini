<?php

namespace spec\Indigo\Ini\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RendererExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Ini\Exception\RendererException');
    }

    function it_is_a_runtime_exception()
    {
        $this->shouldHaveType('RuntimeException');
    }
}
