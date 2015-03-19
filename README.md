Erazr
===============

Version: **1.0.0**


### Installation

    git clone https://github.com/jock91/erazr.git

### Configuration
##### API facebook
Ajouter votre facebook ID et Client secret
```yaml
    # app/config/parameters.yml
    parameters:
        facebook_client_id:     # Client Id
        facebook_client_secret: # Client secret
```
##### Asset installation
```bash
    php app/console assets:install
```
##### Bundles
Installer composer https://getcomposer.org/download/
```bash
    php composer.phar update
```
Déclarer les bundles dans app/appKernel.php
```php
    $bundles = array(
        # ...
        new Erazr\Bundle\SiteBundle\ErazrSiteBundle(),
        new FOS\UserBundle\FOSUserBundle(),
        new Erazr\Bundle\UserBundle\ErazrUserBundle(),
        new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
        new Erazr\Bundle\ChatBundle\ErazrChatBundle(),
        new P2\Bundle\RatchetBundle\P2RatchetBundle(),
    );
```

### Déploiement de la base de donnée

```bash
    php app/console doctrine:database:create
    php app/console doctrine:schema:update 
```

#### À vous de jouer ! 

    Commencez par créer un compte dans la partie inscription ou via la connexion facebook


