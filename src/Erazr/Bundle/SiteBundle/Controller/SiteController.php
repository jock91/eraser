<?php

namespace Erazr\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Erazr\Bundle\SiteBundle\Entity\Post;
use Erazr\Bundle\SiteBundle\Entity\Comment;
use Erazr\Bundle\SiteBundle\Form\PostType;
use Erazr\Bundle\SiteBundle\Form\CommentType;



class SiteController extends Controller
{

    /**
     * @Route("/", name="_home")
     * @Template("ErazrSiteBundle:Erazr:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $this->deletePostTimeOut();
    	$posts = $this->getDoctrine()
      		->getManager()
      		->getRepository('ErazrSiteBundle:Post')
      		->findAllPostOrderedByDate('desc')
    	;

        $post = new Post();
        $post->setUser($this->getUser());
        
        $form = $this->createForm(new PostType(), $post, array(
            'action' => $this->generateUrl('_home'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter le post'));
        $form->handleRequest($this->getRequest());

        if($request->isMethod("POST")){
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager(); 
                
                $hourTimer = $post->getTimer();
                $interval= $hourTimer->format("H:i:s");
                $now = new \DateTime("now");
                $now->add(new \DateInterval("P0000-00-00T".$interval));
                $newTimer = $now->format('Y-m-d H:i:s');
     
                $post->setTimer(new \DateTime($newTimer));


                $em->persist($post);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Ton message est bien posté !');
                return $this->redirect($this->generateUrl('_home'));
            } 
        }

		return array(
            'posts' => $posts,
            'form'   => $form->createView(),
            );
    }

    /**
     * @Route("/post/{id}", name="_postView")
     * @Template("ErazrSiteBundle:Erazr:view.html.twig")
     * @Method({"GET","POST"})
     * @ParamConverter("post", class="ErazrSiteBundle:Post")
     */
    public function viewAction(Request $request,Post $post)
    {

        $em = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $form = $this->createCreateForm($comment, $post->getId());
        $form->handleRequest($request);
        $comment->setUser($this->getUser());
        $comment->setCreated(new \DateTime('now'));

        if ($form->isValid()) {
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('_postView', array('id' => $post->getId())));
        }
    	
    	// On récupere les commentaires
    	$comments = $em->getRepository('ErazrSiteBundle:Comment')
    		->findByPost($post);

		return array(
			'post' => $post,
			'comments' => $comments,
            'form'   => $form->createView()
			);
    }


    /**
     * Creates a form to create a Comment entity.
     *
     * @param Comment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comment $comment, $id)
    {
        $form = $this->createForm(new CommentType(), $comment, array(
            'action' => $this->generateUrl('_postView', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    

    protected function deletePostTimeOut()
    {   
        $em = $this->getDoctrine()->getManager();

        $postTimer = $em->getRepository('ErazrSiteBundle:Post')->findAllPostOrderedByTimer();

        if (is_array($postTimer)){
            foreach ($postTimer as $pT) {
                $em->remove($pT);
                $em->flush();
            } 
        } else if ($postTimer instanceof Post) {
            $em->remove($postTimer);
            $em->flush();
        }
    }
}