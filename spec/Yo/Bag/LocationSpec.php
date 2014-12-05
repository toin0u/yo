<?php

namespace spec\Yo\Bag;

class LocationSpec extends \PhpSpec\ObjectBehavior
{
    const LATITUDE  = 55.699953;
    const LONGITUDE = 12.552736;

    function let()
    {
        $this->beConstructedWith(self::LATITUDE, self::LONGITUDE);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Yo\Bag\Location');
    }

    function it_returns_the_location_value()
    {
        $this->getValue()->shouldReturn(sprintf('%f,%f', self::LATITUDE, self::LONGITUDE));
    }

    function it_returns_location_key_name()
    {
        $this->getKey()->shouldReturn('location');
    }
}
