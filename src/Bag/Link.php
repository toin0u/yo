<?php

/**
 * This file is part of the Yo library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yo\Bag;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class Link implements \Yo\Bag
{
    /**
     * @var string
     */
    private $value;

    /**
     * Constructor.
     *
     * @param  string                    $link
     * @throws \InvalidArgumentException
     */
    public function __construct($link)
    {
        if (false === filter_var($link, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(sprintf('The given link `%s` is not a valid url.', $link));
        }

        $this->value = $link;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'link';
    }
}
