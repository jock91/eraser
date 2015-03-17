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
    /**
     * @Route("/chatot")
     * @Template()
     */
    public function indexAction()
    {
        $nodesocket = new NodeSocket;

	    $event = $this->get('service_nodesocket')->getFrameFactory()->createEventFrame();
	    $event->setEventName('message');
	    $event['url'] = "uri";
	    $event['time'] = date("d.m.Y H:i");
	    $event['message'] = 'Hello';
	    $event->send();

	    return $this->render('ErazrChatBundle:Default:index.html.twig');
    }
}
