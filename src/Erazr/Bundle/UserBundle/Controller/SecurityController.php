<?php

namespace Erazr\Bundle\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;


class SecurityController extends BaseSecurityController
{
    public function loginAction()
    {
        $url = $this->container->get('router')->generate('_home');

        if($this->container->get('security.context')->isGranted('ROLE_USER')){
            return new RedirectResponse($url);
        }
        return parent::loginAction();
    }
}
