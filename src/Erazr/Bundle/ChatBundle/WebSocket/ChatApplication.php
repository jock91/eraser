<?php

namespace Erazr\Bundle\ChatBundle\WebSocket;

use P2\Bundle\RatchetBundle\WebSocket\ConnectionEvent;
use P2\Bundle\RatchetBundle\WebSocket\Payload;
use P2\Bundle\RatchetBundle\WebSocket\ApplicationInterface;

class ChatApplication implements ApplicationInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'chat.send' => 'onSendMessage'
        );
    }

    public function onSendMessage(ConnectionEvent $event)
    {
        $client = $event->getConnection()->getClient()->jsonSerialize();
        $message = $event->getPayload()->getData();

        $event->getConnection()->broadcast(
            new Payload(
                'chat.message',
                array(
                    'client' => $client,
                    'message' => $message
                )
            )
        );

        $event->getConnection()->emit(
            new Payload(
                'chat.message.sent',
                array(
                    'client' => $client,
                    'message' => $message
                )
            )
        );
    }
}