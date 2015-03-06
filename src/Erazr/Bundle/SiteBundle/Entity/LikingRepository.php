<?php

namespace Erazr\Bundle\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Erazr\Bundle\UserBundle\Entity\User;

/**
 * LikingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LikingRepository extends EntityRepository
{
	public function findLikeByUserPost(User $user,Post $post)
    {
       return $this->createQueryBuilder('l')
		    ->where('l.user = :user')
		    ->andwhere('l.post = :post')
		    ->setParameter(':user', $user)
		    ->setParameter(':post', $post)
		    ->getQuery()
            ->getResult();
    }
}
