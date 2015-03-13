<?php

namespace Erazr\Bundle\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Erazr\Bundle\UserBundle\Entity\User;
use Erazr\Bundle\SiteBundle\Entity\Post;


/**
 * Liking
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Erazr\Bundle\SiteBundle\Entity\LikingRepository")
 */
class Liking
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Erazr\Bundle\UserBundle\Entity\User", inversedBy="likings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Erazr\Bundle\SiteBundle\Entity\Post", inversedBy="likings")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=false)
     */
    protected $post;


    /**
     * @ORM\OneToMany(targetEntity="Erazr\Bundle\SiteBundle\Entity\Notification", mappedBy="liking" , cascade={"remove"})
     **/
    private $notifications;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isLiked
     *
     * @param boolean $isLiked
     * @return Liking
     */
    public function setIsLiked($isLiked)
    {
        $this->isLiked = $isLiked;

        return $this;
    }

    /**
     * Get isLiked
     *
     * @return boolean 
     */
    public function getIsLiked()
    {
        return $this->isLiked;
    }

    /**
     * Set user
     *
     * @param \Erazr\Bundle\UserBundle\Entity\User $user
     * @return Liking
     */
    public function setUser(\Erazr\Bundle\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Erazr\Bundle\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set post
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Post $post
     * @return Liking
     */
    public function setPost(\Erazr\Bundle\SiteBundle\Entity\Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Erazr\Bundle\SiteBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }
}
