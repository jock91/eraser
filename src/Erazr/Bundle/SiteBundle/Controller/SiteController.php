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

use Erazr\Bundle\SiteBundle\Entity\Post;
use Erazr\Bundle\SiteBundle\Form\PostType;



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

        $form->add('submit', 'submit', array('label' => 'Poster'));
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
     * @Method("GET")
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

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/modifier/post/{id}", name="post_edit")
     * @Method("GET")
     * @Template("ErazrSiteBundle:Post:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ErazrSiteBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Post entity.
    *
    * @param Post $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Post $entity)
    {
        $form = $this->createForm(new PostType(), $entity, array(
            'action' => $this->generateUrl('post_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Post entity.
     *
     * @Route("/{id}", name="post_update")
     * @Method("PUT")
     * @Template("ErazrSiteBundle:Post:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ErazrSiteBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('post_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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