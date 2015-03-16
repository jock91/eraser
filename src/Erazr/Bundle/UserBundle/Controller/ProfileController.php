<?php
namespace Erazr\Bundle\UserBundle\Controller;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Erazr\Bundle\UserBundle\Entity\User;
use Erazr\Bundle\SiteBundle\Entity\Notification;
class ProfileController extends BaseController
{

	public function getDoctrine()
{
    return $this->container->get('doctrine');
}

	protected function deleteNotification ($typ, $search) {
		$em = $this->getDoctrine()->getManager();
		$user =	$user = $this->container->get('security.context')->getToken()->getUser();

		$notif = $em->getRepository('ErazrSiteBundle:Notification')->findNotifByUserType($user, $typ, $search);
		foreach ($notif as $n) {
			$em->remove($n);
		}
		$em->flush();
	}

	/**
	* Show the user
	*/
	public function showAction(User $user=null)
	{
		if( $user == null ) {
			$user = $this->container->get('security.context')->getToken()->getUser();
			if (!is_object($user) || !$user instanceof UserInterface) {
				throw new AccessDeniedException('This user does not have access to this section.');
			}
		}
		$this->deleteNotification('friend', 'friend');
		return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'), array('user' => $user));
	}

	/**
	* Edit the user
	*/
	public function editAction(User $user=null)
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		$form = $this->container->get('fos_user.profile.form');
		$formHandler = $this->container->get('fos_user.profile.form.handler');
		$process = $formHandler->process($user);
		if ($process) {
			$this->setFlash('fos_user_success', 'profile.flash.updated');
			return new RedirectResponse($this->getRedirectionUrl($user));
		}
		return $this->container->get('templating')->renderResponse(
			'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
			array('form' => $form->createView())
		);
	}

	/**
	* Generate the redirection url when editing is completed.
	*
	* @param \FOS\UserBundle\Model\UserInterface $user
	*
	* @return string
	*/
	protected function getRedirectionUrl(UserInterface $user)
	{
		return $this->container->get('router')->generate('fos_user_profile_show');
	}

	/**
	* @param string $action
	* @param string $value
	*/
	protected function setFlash($action, $value)
	{
		$this->container->get('session')->getFlashBag()->set($action, $value);
	}
}