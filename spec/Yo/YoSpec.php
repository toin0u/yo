<?php

namespace spec\Yo;

use Ivory\HttpAdapter\ConfigurationInterface;
use Ivory\HttpAdapter\HttpAdapterException;
use Ivory\HttpAdapter\Message\InternalRequestInterface;
use Ivory\HttpAdapter\Message\MessageInterface;
use Ivory\HttpAdapter\Message\Response;
use Ivory\HttpAdapter\Message\Stream\StringStream;
use Yo\Bag\Link;
use Yo\Bag\Location;
use Yo\Yo;


class YoSpec extends \PhpSpec\ObjectBehavior
{
    const API_KEY = 'my_api_key';

    /**
     * @param Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param Ivory\HttpAdapter\ConfigurationInterface                   $configuration
     * @param Ivory\HttpAdapter\HttpAdapterInterface                     $adapter
     */
    function let($eventDispatcher, $configuration, $adapter)
    {
        $configuration->setTimeout(Yo::TIMEOUT)->shouldBeCalled();
        $configuration->setUserAgent(Yo::USER_AGENT)->shouldBeCalled();
        $configuration->setEncodingType(ConfigurationInterface::ENCODING_TYPE_URLENCODED)->shouldBeCalled();
        $configuration->getEventDispatcher()->willReturn($eventDispatcher);

        $adapter->getConfiguration()->willReturn($configuration);
        $adapter->setConfiguration($configuration)->shouldBeCalled();

        $this->beConstructedWith($adapter, self::API_KEY);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Yo\Yo');
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface      $adapter
     * @param Ivory\HttpAdapter\Message\ResponseInterface $response
     * @param Psr\Http\Message\StreamableInterface        $stream
     */
    function it_sends_a_yo_to_a_given_username_without_anything($adapter, $response, $stream)
    {
        $stream->getContents()->willReturn('{"result" : "OK"}');
        $response->getBody()->willReturn($stream);
        $adapter
            ->send(
                sprintf('%s/yo', Yo::ENDPOINT),
                InternalRequestInterface::METHOD_POST,
                array(),
                array(
                    'api_token' => self::API_KEY,
                    'username'  => 'FOOBAR',
                )
            )
            ->willReturn($response)
        ;

        $this->user('foobar')->shouldReturn(true);
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface $adapter
     */
    function it_throws_an_exception_correctly_when_there_is_no_response($adapter)
    {
        $exception = new HttpAdapterException('An error occurred when fetching the URL "https://api.justyo.co/yo" with the adapter "curl" ("SSL certificate problem: Invalid certificate chain").');
        $exception->setResponse();

        $adapter
            ->send(
                sprintf('%s/yo', Yo::ENDPOINT),
                InternalRequestInterface::METHOD_POST,
                array(),
                array(
                    'api_token' => self::API_KEY,
                    'username'  => 'WHOAREYOU',
                )
            )
            ->willThrow($exception)
        ;

        $this
            ->shouldThrow(new \RuntimeException(
                '[Yo] something went wrong `An error occurred when fetching the URL "https://api.justyo.co/yo" with the adapter "curl" ("SSL certificate problem: Invalid certificate chain").` the response body was `not defined` !'
            ))
            ->duringUser('whoareyou')
        ;
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface $adapter
     */
    function it_throws_an_exception_when_username_does_not_exist($adapter)
    {
        $stream    = new StringStream('{"code": 141, "error": "NO SUCH USER"}');
        $response  = new Response(400, '', MessageInterface::PROTOCOL_VERSION_1_1, array(), $stream, array());
        $exception = new HttpAdapterException('An error occurred when fetching the URL "https://api.justyo.co/yo" with the adapter "curl" ("Status code: 400").');
        $exception->setResponse($response);

        $adapter
            ->send(
                sprintf('%s/yo', Yo::ENDPOINT),
                InternalRequestInterface::METHOD_POST,
                array(),
                array(
                    'api_token' => self::API_KEY,
                    'username'  => 'WHOAREYOU',
                )
            )
            ->willThrow($exception)
        ;

        $this
            ->shouldThrow(new \RuntimeException(
                '[Yo] something went wrong `An error occurred when fetching the URL "https://api.justyo.co/yo" with the adapter "curl" ("Status code: 400").` the response body was `{"code": 141, "error": "NO SUCH USER"}` !'
            ))
            ->duringUser('whoareyou')
        ;
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface $adapter
     */
    function it_throws_an_exception_when_the_api_token_is_invalid($adapter)
    {
        $this->beConstructedWith($adapter, 'wrong_api_token');

        $stream    = new StringStream('{"code": 141, "error": "TRY AGAIN LATER."}');
        $response  = new Response(400, '', MessageInterface::PROTOCOL_VERSION_1_1, array(), $stream, array());
        $exception = new HttpAdapterException('An error occurred when fetching the URL "https://api.justyo.co/yo" with the adapter "curl" ("Status code: 400").');
        $exception->setResponse($response);

        $adapter
            ->send(
                sprintf('%s/yo', Yo::ENDPOINT),
                InternalRequestInterface::METHOD_POST,
                array(),
                array(
                    'api_token' => 'wrong_api_token',
                    'username'  => 'FOOBAR',
                )
            )
            ->willThrow($exception)
        ;

        $this
            ->shouldThrow(new \RuntimeException(
                '[Yo] something went wrong `An error occurred when fetching the URL "https://api.justyo.co/yo" with the adapter "curl" ("Status code: 400").` the response body was `{"code": 141, "error": "TRY AGAIN LATER."}` !'
            ))
            ->duringUser('foobar')
        ;
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface      $adapter
     * @param Ivory\HttpAdapter\Message\ResponseInterface $response
     * @param Psr\Http\Message\StreamableInterface        $stream
     */
    function it_sends_a_yo_to_a_given_username_with_a_link($adapter, $response, $stream)
    {
        $link = new Link('http://sbin.dk/');

        $stream->getContents()->willReturn('{"result":"OK"}');
        $response->getBody()->willReturn($stream);
        $adapter
            ->send(
                sprintf('%s/yo', Yo::ENDPOINT),
                InternalRequestInterface::METHOD_POST,
                array(),
                array(
                    'api_token'     => self::API_KEY,
                    'username'      => 'FOOBAR',
                    $link->getKey() => $link->getValue(),
                )
            )
            ->willReturn($response)
        ;

        $this->user('foobar', $link)->shouldReturn(true);
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface      $adapter
     * @param Ivory\HttpAdapter\Message\ResponseInterface $response
     * @param Psr\Http\Message\StreamableInterface        $stream
     */
    function it_sends_a_yo_to_a_given_username_with_a_location($adapter, $response, $stream)
    {
        $location = new Location(55.699953, 12.552736);

        $stream->getContents()->willReturn('{"result":"OK"}');
        $response->getBody()->willReturn($stream);
        $adapter
            ->send(
                sprintf('%s/yo', Yo::ENDPOINT),
                InternalRequestInterface::METHOD_POST,
                array(),
                array(
                    'api_token'         => self::API_KEY,
                    'username'          => 'FOOBAR',
                    $location->getKey() => $location->getValue(),
                )
            )
            ->willReturn($response)
        ;

        $this->user('foobar', $location)->shouldReturn(true);
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface $adapter
     */
    function it_sends_a_yo_to_all_subscribers_without_a_link($adapter)
    {
        $adapter
            ->send(
                sprintf('%s/yoall', Yo::ENDPOINT),
                InternalRequestInterface::METHOD_POST,
                array(),
                array('api_token' => self::API_KEY)
            )
            ->willReturn(null)
        ;

        $this->all()->shouldReturn(null);
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface $adapter
     */
    function it_sends_a_yo_to_all_subscribers_with_a_link($adapter)
    {
        $link = new Link('http://sbin.dk/');

        $adapter
            ->send(
                sprintf('%s/yoall', Yo::ENDPOINT),
                InternalRequestInterface::METHOD_POST,
                array(),
                array(
                    'api_token'     => self::API_KEY,
                    $link->getKey() => $link->getValue(),
                )
            )
            ->willReturn(null)
        ;

        $this->all($link)->shouldReturn(null);
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface $adapter
     */
    function it_throws_an_exception_when_the_rate_limit_is_exceeded($adapter)
    {
        $stream    = new StringStream('"Rate limit exceeded. Only one Yo all once per minute."');
        $response  = new Response(400, '', MessageInterface::PROTOCOL_VERSION_1_1, array(), $stream, array());
        $exception = new HttpAdapterException('An error occurred when fetching the URL "https://api.justyo.co/yoall" with the adapter "curl" ("Status code: 400").');
        $exception->setResponse($response);

        $adapter
            ->send(
                sprintf('%s/yoall', Yo::ENDPOINT),
                InternalRequestInterface::METHOD_POST,
                array(),
                array('api_token' => self::API_KEY)
            )
            ->willThrow($exception)
        ;

        $this
            ->shouldThrow(new \RuntimeException(
                '[Yo] something went wrong `An error occurred when fetching the URL "https://api.justyo.co/yoall" with the adapter "curl" ("Status code: 400").` the response body was `"Rate limit exceeded. Only one Yo all once per minute."` !'
            ))
            ->duringAll()
        ;
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface      $adapter
     * @param Ivory\HttpAdapter\Message\ResponseInterface $response
     */
    function it_returns_the_total_number_of_subscribers($adapter, $response)
    {
        $response->getBody()->willReturn('{"result":123}');
        $adapter
            ->send(
                sprintf('%s/subscribers_count/?api_token=%s', Yo::ENDPOINT, self::API_KEY),
                InternalRequestInterface::METHOD_GET,
                array(),
                array()
            )
            ->willReturn($response)
        ;

        $this->total()->shouldReturn(123);
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface      $adapter
     * @param Ivory\HttpAdapter\Message\ResponseInterface $response
     */
    function it_returns_true_when_a_given_username_exists($adapter, $response)
    {
        $response->getBody()->willReturn('{"result":"EXISTS"}');
        $adapter
            ->send(
                sprintf('%s/check_username/?api_token=%s&username=FOOBAR', Yo::ENDPOINT, self::API_KEY),
                InternalRequestInterface::METHOD_GET,
                array(),
                array()
            )
            ->willReturn($response)
        ;

        $this->exists('foobar')->shouldReturn(true);
    }

    /**
     * @param Ivory\HttpAdapter\HttpAdapterInterface      $adapter
     * @param Ivory\HttpAdapter\Message\ResponseInterface $response
     */
    function it_returns_false_when_a_given_username_done_not_exist($adapter, $response)
    {
        $response->getBody()->willReturn('{"result":"DOES NO EXISTS"}');
        $adapter
            ->send(
                sprintf('%s/check_username/?api_token=%s&username=FOOBAR', Yo::ENDPOINT, self::API_KEY),
                InternalRequestInterface::METHOD_GET,
                array(),
                array()
            )
            ->willReturn($response)
        ;

        $this->exists('foobar')->shouldReturn(false);
    }
}
