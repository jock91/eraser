<?php

namespace Erazr\Bundle\ChatBundle\WebSocket;

use P2\Bundle\RatchetBundle\WebSocket\Server\ApplicationInterface;

class Application implements ApplicationInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'erazr.websocket.some.event' => 'onSomeEvent'
            // ...
        );
    }

    // put your event handler code here ...
}