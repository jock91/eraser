<?php

namespace Erazr\Bundle\ChatBundle\WebSocket\Loop;

use P2\Bundle\RatchetBundle\WebSocket\Server\Loop\PeriodicTimerInterface;

class CustomTimer implements PeriodicTimerInterface
{
    /**
     * Returns the interval for this timer
     *
     * @return int
     */
    public function getInterval()
    {
        return 60; // execute this timer once per minute
    }

    /**
     * Returns the callback.
     *
     * @return callable
     */
    public function getCallback()
    {
        return function() {
            // do something
        };
    }

    /**
     * Returns a unique name for this timer.
     *
     * @return string
     */
    public function getName()
    {
        return 'custom_timer';
    }
}