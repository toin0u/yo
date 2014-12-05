<?php

namespace spec\Yo\Bag;

class LinkSpec extends \PhpSpec\ObjectBehavior
{
    const LINK = 'http://foo';

    function let()
    {
        $this->beConstructedWith(self::LINK);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Yo\Bag\Link');
    }

    function it_throws_an_exception_if_not_a_link()
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('The given link `foo` is not a valid url.'))
            ->during('__construct', array('foo'))
        ;
    }

    function it_returns_the_link_value()
    {
        $this->getValue()->shouldReturn(self::LINK);
    }

    function it_returns_link_key_name()
    {
        $this->getKey()->shouldReturn('link');
    }
}
