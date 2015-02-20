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
    	$posts = $this->getDoctrine()
      		->getManager()
      		->getRepository('ErazrSiteBundle:Post')
      		->findAll()
    	;
		return array('posts' => $posts);
    }

    /**
     * @Route("/post/{id}", name="_postView")
     * @Template("ErazrSiteBundle:Erazr:view.html.twig")
     */
    public function viewAction($id)
    {
    	$em = $this->getDoctrine()->getManager();

    	// on recupere le post
    	$post = $em->getRepository('ErazrSiteBundle:Post')
      		->find($id)
    	;

    	// si le post existe pas message erreur
    	if ($post === null) {
    		throw $this->createNotFoundException("Le post n°".$id." n'existe pas.");
    	}

    	// On récupere les commentaires
    	$comments = $em->getRepository('ErazrSiteBundle:Comment')
    		->findByPost($post);

		return array(
			'post' => $post,
			'comments' => $comments
			);
    }
}