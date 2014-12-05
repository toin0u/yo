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
class Location implements \Yo\Bag
{
    /**
     * @var string
     */
    private $value;

    /**
     * Constructor.
     *
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->value = sprintf('%f,%f', $latitude, $longitude);
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
        return 'location';
    }
}
