<?php

namespace Erazr\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class SiteController extends Controller
{
    /**
     * @Route("/", name="_home")
     * @Template("ErazrSiteBundle:Erazr:index.html.twig")
     */
    public function indexAction()
    {
    	$name = 'kevin';
		return array('name' => $name);
    }
}
