Erazr
===============

Version: **1.0.0**


### Installation

    git clone https://github.com/jock91/erazr.git


### Configuration

Ajouter votre facebook ID et Client secret
```php
    parameters:
        facebook_client_id: 514256792045832
        facebook_client_secret: f0ed18907a98f84b7c5635543ca34d87


```

### Getting started



```php
# src/Acme/Bundle/ChatBundle/WebSocket/Application.php
<?php

namespace Acme\Bundle\ChatBundle\WebSocket;

use P2\Bundle\RatchetBundle\WebSocket\Server\ApplicationInterface;

class Application implements ApplicationInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'acme.websocket.some.event' => 'onSomeEvent'
            // ...
        );
    }

    // put your event handler code here ...
}

```

#### Service DI Configuration

Create a service definition for your websocket application. Tag your service definition with `kernel.event_subscriber` and `p2_ratchet.application` to register the application to the server.

The service definition may look like this:
```yaml
# src/Acme/Bundle/ChatBundle/Resources/config/services.yml
services:

    # websocket chat application
    websocket_chat:
        class: Acme\Bundle\ChatBundle\WebSocket\ChatApplication
        tags:
            - { name: kernel.event_subscriber }
            - { name: p2_ratchet.application }
```


### Command Line Tool

```bash
php app/console socket:server:start [port] [address]
```

