<?php

namespace Erazr\Bundle\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


/**
 * NotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificationRepository extends EntityRepository
{
	public function findNotifByUserType($destinataireId, $type, $search)
    {
        
    	$qb = $this ->createQueryBuilder('n')
                   ->where('n.destinataire = :destinataireId');

       if($type === 'liking'){
           $qb->andwhere('n.liking = :search');
       }
       if($type === 'comment'){
           $qb->andwhere('n.comment = :search');
       }
       if($type === 'friend'){
           $qb->andwhere('n.friend = :search');
       }

       return $qb->setParameter('destinataireId', $destinataireId)
       ->setParameter('search', $search)
       ->getQuery()
       ->getResult();
    }
	public function findNotifByUserCommPost($destinataireId, $type, $search, $post)
    {
        
    	$qb = $this ->createQueryBuilder('n')
                   ->where('n.destinataire = :destinataireId');

       if($type === 'comment'){
           $qb->andwhere('n.comment = :search');
           $qb->andwhere('n.post = :post');
       }

       return $qb->setParameter('destinataireId', $destinataireId)
       ->setParameter('search', $search)
       ->setParameter('post', $post)
       ->getQuery()
       ->getResult();
    }
}