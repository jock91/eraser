<?php

namespace Erazr\Bundle\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Erazr\Bundle\UserBundle\Entity;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Erazr\Bundle\SiteBundle\Entity\PostRepository")
 */
class Post
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
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timer", type="datetime")
     */
    private $timer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="like", type="smallint")
     */
    private $like;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=10)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity="Erazr\Bundle\UserBundle\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

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
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Post
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set timer
     *
     * @param \DateTime $timer
     * @return Post
     */
    public function setTimer($timer)
    {
        $this->timer = $timer;

        return $this;
    }

    /**
     * Get timer
     *
     * @return \DateTime 
     */
    public function getTimer()
    {
        return $this->timer;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Post
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set like
     *
     * @param \tinyint $like
     * @return Post
     */
    public function setLike(\tinyint $like)
    {
        $this->like = $like;

        return $this;
    }

    /**
     * Get like
     *
     * @return \tinyint 
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Set user
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\User $user
     * @return Post
     */
    public function setUser(\Erazr\Bundle\SiteBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Erazr\Bundle\SiteBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
