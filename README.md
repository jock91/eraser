Erazr
===============

Version: **1.0.0**


### Installation

    git clone https://github.com/jock91/erazr.git

### Configuration
## API facebook
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
    php app/console doctrine:schema:update 
```

#### À vous de jouer ! 

    Commencez par créer un compte dans la partie inscription ou via la connexion facebook


