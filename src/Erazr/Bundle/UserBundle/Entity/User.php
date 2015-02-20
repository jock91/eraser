<?php

namespace Erazr\Bundle\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Erazr\Bundle\SiteBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Erazr\Bundle\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Erazr\Bundle\SiteBundle\Entity\Post", mappedBy="user", cascade={"persist"})
     */
    protected $posts;

    /**
    * @ORM\OneToMany(targetEntity="Erazr\Bundle\SiteBundle\Entity\Comment", mappedBy="user", cascade={"persist"})
    */
    protected $comments;

    /**
     *
     * @ORM\Column(name="totalLike", type="integer", nullable=true)
     */
    private $totalLike;

    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
    }

    
}
