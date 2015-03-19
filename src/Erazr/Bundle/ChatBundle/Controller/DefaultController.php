<?php

namespace Erazr\Bundle\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Reactorcoder\Symfony2NodesocketBundle\Library\php\NodeSocket as NodeSocket;

class DefaultController extends Controller
{
    /**
     * @Route("/chat/{username}")
     * @Template()
     */
    public function chatAction()
    {
        return array('' => '');
    }

}
