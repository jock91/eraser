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
use Erazr\Bundle\SiteBundle\Entity\Liking;
use Erazr\Bundle\SiteBundle\Form\PostType;
use Erazr\Bundle\SiteBundle\Form\CommentType;
use Erazr\Bundle\SiteBundle\Form\SearchType;



class SiteController extends Controller
{   


    /**
    * @Method({"GET","POST"})
    * @Route("/add/friend/{id}", name="_addFriend")
    */
    public function addFriendAction($id, Request $request ){
        $em = $this->getDoctrine()->getManager();
        $userFriend = $em->getRepository('ErazrUserBundle:User')->find($id);
        if( $userFriend ===  $this->getUser() ) {
            $this->get('session')->getFlashBag()->add('error', '<b>' . $this->getUser()."</b> arrête d'essayer de t'ajouter en ami !"); 
            return $this->redirect($request->headers->get('referer'));
        } else {
        
        $em = $this->getDoctrine()->getManager();

        $userFriend = $em->getRepository('ErazrUserBundle:User')->find($id);

        $newFriend = $this->getUser()->addMyFriend($userFriend);

        $em->persist($newFriend);

        $em->flush();

        $this->get('session')->getFlashBag()->add('success', '<b>' . $userFriend ."</b> est maintenant votre ami !");

        return $this->redirect($request->headers->get('referer'));
        }
    }

    /**
    * @Method({"DELETE", "GET"})
    * @Route("/remove/friend/{id}", name="_removeFriend")
    */
    public function deleteFriendAction($id, Request $request ){
        
        $em = $this->getDoctrine()->getManager();

        $userFriend = $em->getRepository('ErazrUserBundle:User')->find($id);

        $removeFriend = $this->getUser();
        $removeFriend->removeMyFriend($userFriend);

        $em->persist($removeFriend);
        $em->flush();

        $this->get('session')->getFlashBag()->add('warning', '<b>' . $userFriend ."</b> n'est plus votre ami !");
        return $this->redirect($request->headers->get('referer'));
        
    }





    /**
    * @Method({"GET","POST"})
    * @Route("/voted/{id}", name="_voted")
    */
    public function LikeAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('ErazrSiteBundle:Post')->find($id);
        $liker = $em->getRepository('ErazrSiteBundle:Liking')->findLikeByUserPost($this->getUser(), $post);

        if( !empty($liker) ){
            return $this->redirect($request->headers->get('referer'));
        } else {
            $post = $em->getRepository('ErazrSiteBundle:Post')->find($id);

            if(!$post){
                $this->get('session')->getFlashBag()->add('error', "Ce post n'existe pas !"); 
                return $this->redirect($this->generateUrl('_home'));
            }else {
                $liking = new Liking();
                $liking->setUser($this->getUser());
                $liking->setPost($post); 
                
                $em->persist($liking);
                $em->flush();
                return $this->redirect($request->headers->get('referer'));
            }
        }
        
    }   

    /**
    * @Method({"GET","DELETE"})
    * @Route("/dislike/{id}", name="_dislike")
    * 
    */
    public function DislikeAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('ErazrSiteBundle:Post')->find($id);
        $liker = $em->getRepository('ErazrSiteBundle:Liking')->findLikeByUserPost($this->getUser(), $post);
        
        foreach ($liker as $lik) {
            $em->remove($lik);
        }
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
    * @Route("/search", name="_search")
    * @Template("ErazrSiteBundle:Erazr:aside.html.twig")
    */
    public function asideAction(Request $request) {
        $formSearch = $this->createForm(new SearchType());
            if(isset($request->get("erazr_bundle_search")["search"])){
                $UserSearched = $this->getDoctrine()->getRepository('ErazrUserBundle:User')->findAllUserBySearch($request->get("erazr_bundle_search")["search"]);
            }else {
                $UserSearched = null;
            }        
        
        return array(
            'formSearch' => $formSearch->createView(),
            'myUser' => $UserSearched,
            );
    }



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
                $content = nl2br($post->getContent());
                $post->setContent($content);
                
                $hourTimer = $post->getTimer();


                if($hourTimer === null) {
                    $interval = "00:10:00";
                } else {
                    $interval= $hourTimer->format("H:i:s");
                }

                
                
                $now = new \DateTime("now");
                $now->add(new \DateInterval("P0000-00-00T".$interval));
                $newTimer = $now->format('Y-m-d H:i:s');
     
                $post->setTimer(new \DateTime($newTimer));


                $em->persist($post);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Ton message a bien été posté');
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
        $form = $this->createCommentForm($comment, $post->getId());
        $form->handleRequest($request);
        $comment->setUser($this->getUser());
        $comment->setCreated(new \DateTime('now'));

        if ($form->isValid()) {
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Ton commentaire a bien été posté');
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
    private function createCommentForm(Comment $comment, $id)
    {
        $form = $this->createForm(new CommentType(), $comment, array(
            'action' => $this->generateUrl('_postView', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter un commentaire'));

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