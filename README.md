Erazr
===============

Version: **1.0.0**


### Installation

    git clone https://github.com/jock91/erazr.git


### Configuration

Ajouter votre facebook ID et Client secret
```yaml
    # app/config/parameters.yml
    parameters:
        facebook_client_id:     # Client Id
        facebook_client_secret: # Client secret

```

### Déploiement de la base de donnée

```bash
    php app/console doctrine:database:create
    php app/console doctrine:database:update 
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

