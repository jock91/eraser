<?php
namespace Erazr\Bundle\UserBundle\Security;

use FOS\UserBundle\Security\UserProvider as fosprovider;
use P2\Bundle\RatchetBundle\WebSocket\Client\ClientProviderInterface;
use P2\Bundle\RatchetBundle\WebSocket\Client\ClientInterface;


class UserProvider extends fosprovider implements ClientProviderInterface
{
    /**
    * Returns a client found by the access token.
    *
    * @param string $accessToken
    *
    * @return ClientInterface
    */
    public function findByAccessToken($accessToken){
       return $this->userManager->findUserBy(array('accessToken' => $accessToken));
    }

    /**
    * Updates the given client in the underlying data layer.
    *
    * @param ClientInterface $client
    * @return void
    */
    public function updateClient(ClientInterface $client)
    {
        $this->userManager->updateUser($client);
    }
}
