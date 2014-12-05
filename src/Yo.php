<?php

/**
 * This file is part of the Yo library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yo;

use Ivory\HttpAdapter\ConfigurationInterface;
use Ivory\HttpAdapter\Event\Subscriber\StatusCodeSubscriber;
use Ivory\HttpAdapter\HttpAdapterInterface;
use Ivory\HttpAdapter\Message\InternalRequestInterface;
use Yo\Bag\Link;
use Yo\Bag\Location;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class Yo
{
    const ENDPOINT   = 'https://api.justyo.co';
    const USER_AGENT = 'Yo PHP!';
    const TIMEOUT    = 10;

    /**
     * Private API token at http://dev.justyo.co/
     *
     * @var string
     */
    private $apiToken;

    /**
     * @var HttpAdapterInterface
     */
    private $adapter;

    /**
     * Constructor.
     *
     * @param HttpAdapterInterface $adapter
     * @param string               $apiToken
     */
    public function __construct(HttpAdapterInterface $adapter, $apiToken)
    {
        $this->adapter  = $adapter;
        $this->apiToken = $apiToken;

        $configuration = $this->adapter->getConfiguration();
        $configuration->setTimeout(self::TIMEOUT);
        $configuration->setUserAgent(self::USER_AGENT);
        $configuration->setEncodingType(ConfigurationInterface::ENCODING_TYPE_URLENCODED);
        $configuration->getEventDispatcher()->addSubscriber(new StatusCodeSubscriber);
        $this->adapter->setConfiguration($configuration);
    }

    /**
     * Send a Yo to an user with or without a Bag object.
     *
     * @see http://docs.justyo.co/v1.0/docs/yo
     *
     * @param  Bag               $bag (optional)
     * @throws \RuntimeException
     * @return boolean
     */
    public function user($username, Bag $bag = null)
    {
        $data = $this->createData(array(
            'api_token' => $this->apiToken,
            'username'  => strtoupper($username),
        ), $bag);

        $response = $this->send('yo', InternalRequestInterface::METHOD_POST, $data);
        $content  = json_decode($response->getBody()->getContents());

        return 'OK' === $content->result;
    }

    /**
     * Send a Yo to all subscribers with or without a Link object.
     * /!\ only one Yo all once per minute /!\
     *
     * @see http://docs.justyo.co/v1.0/docs/yoall
     *
     * @param  Link              $link (optional)
     * @throws \RuntimeException
     */
    public function all(Link $link = null)
    {
        $data = $this->createData(array(
            'api_token' => $this->apiToken,
        ), $link);

        $this->send('yoall', InternalRequestInterface::METHOD_POST, $data);
    }

    /**
     * Get total of subscribers.
     *
     * @see http://docs.justyo.co/v1.0/docs/subscribers_count
     *
     * @throws \RuntimeException
     * @return integer
     */
    public function total()
    {
        $response = $this->send(sprintf('subscribers_count/?api_token=%s', $this->apiToken));
        $content  = json_decode($response->getBody());

        return $content->result;
    }

    /**
     * Check if a given username exists or not.
     *
     * @see http://docs.justyo.co/v1.0/docs/check_username
     *
     * @param  string            $username
     * @throws \RuntimeException
     * @return boolean
     */
    public function exists($username)
    {
        $response = $this->send(sprintf(
            'check_username/?api_token=%s&username=%s',
            $this->apiToken,
            strtoupper($username)
        ));
        $content = json_decode($response->getBody());

        return 'EXISTS' === $content->result;
    }

    private function send($url, $method = InternalRequestInterface::METHOD_GET, array $data = array())
    {
        try {
            return $this->adapter->send(sprintf('%s/%s', self::ENDPOINT, $url), $method, array(), $data);
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf(
                '[Yo] something went wrong `%s` the response body was `%s` !',
                $e->getMessage(),
                $e->getResponse()->getBody()->getContents()
            ));
        }
    }

    private function createData(array $data, Bag $bag = null)
    {
        if (null !== $bag) {
            $data[$bag->getKey()] = $bag->getValue();
        }

        return $data;
    }
}