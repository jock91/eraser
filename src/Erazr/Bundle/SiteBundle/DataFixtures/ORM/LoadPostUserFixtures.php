<?php
	use Doctrine\Common\Persistence\ObjectManager;
	use Doctrine\Common\DataFixtures\FixtureInterface;
	use Symfony\Component\DependencyInjection\ContainerAwareInterface;
	use Symfony\Component\DependencyInjection\ContainerInterface;
	use Erazr\Bundle\SiteBundle\Entity\Post;
	use Erazr\Bundle\UserBundle\Entity\User;


	class LoadPostUserFixture implements FixtureInterface, ContainerAwareInterface
	{
		private $container;

		public function setContainer(ContainerInterface $container = null) {
        	$this->container = $container;
    	}

		 function load(ObjectManager $manager)
		 {
			$i = 1;
			$e = 10;
			$color = array('orange', 'blue', 'green');
			while (($i <= 20) && ($e <= 60)) {
				
				$userManager = $this->container->get('fos_user.user_manager');
				
				$user = $userManager->createUser();
				$user->setUsername('Pseudo_'.$i);
				$user->setEmail('email'.$i."@email.fr");
				$user->setPlainPassword('password'.$i);

				$post = new Post();
				$post->setUser($user);
				$post->setContent('Corps du post '.$i);
				$post->setCreated(new \DateTime("now"));


				$now = new \DateTime("now");
				$interval = "00:".$e.":00";
				
				$now->add(new \DateInterval("P0000-00-00T".$interval));
				$newTimer = $now->format('Y-m-d H:i:s');
	 
				$post->setTimer(new \DateTime($newTimer));
				shuffle($color);
				$post->setColor($color[0]);

				
				$manager->persist($user);
				$manager->persist($post);
				$i++;
				$e++;
		 	}

			$manager->flush();
		}
	}