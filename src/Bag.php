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

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
interface Bag
{
    /**
     * Returns the value for the Bag.
     *
     * @return string
     */
    public function getValue();

    /**
     * Returns the key name for the Bag.
     *
     * @return string
     */
    public function getKey();
}
