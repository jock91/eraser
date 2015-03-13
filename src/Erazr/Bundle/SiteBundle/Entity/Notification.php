<?php

namespace Erazr\Bundle\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Erazr\Bundle\UserBundle\Entity;


/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Erazr\Bundle\SiteBundle\Entity\NotificationRepository")
 */
class Notification
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
     * @ORM\ManyToOne(targetEntity="Erazr\Bundle\SiteBundle\Entity\Liking", inversedBy="notifications" , cascade={"persist"})
     * @ORM\JoinColumn(name="liking_id", referencedColumnName="id", nullable=true)
     **/
    private $like;

    /**
     * @var array
     *
     * @ORM\Column(name="friend", type="string", nullable=true)
     */
    private $friend;


    /**
     * @ORM\ManyToOne(targetEntity="Erazr\Bundle\UserBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="expediteur_id", referencedColumnName="id", nullable=false)
     */
    private $expediteur;

    /**
     * @ORM\ManyToOne(targetEntity="Erazr\Bundle\UserBundle\Entity\User", inversedBy="notifications" , cascade={"persist"})
     * @ORM\JoinColumn(name="destinataire_id", referencedColumnName="id", nullable=false)
     **/
    private $destinataire;
    
    /**
     * @ORM\ManyToOne(targetEntity="Erazr\Bundle\SiteBundle\Entity\Comment", inversedBy="notifications" , cascade={"persist"})
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=true)
     **/
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Erazr\Bundle\SiteBundle\Entity\Post", inversedBy="notifications")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=true)
     **/
    private $post;



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
     * Set expediteur
     *
     * @param \Erazr\Bundle\UserBundle\Entity\User $expediteur
     * @return Notification
     */
    public function setExpediteur(\Erazr\Bundle\UserBundle\Entity\User $expediteur)
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    /**
     * Get expediteur
     *
     * @return \Erazr\Bundle\UserBundle\Entity\User 
     */
    public function getExpediteur()
    {
        return $this->expediteur;
    }

    /**
     * Set destinataire
     *
     * @param \Erazr\Bundle\UserBundle\Entity\User $destinataire
     * @return Notification
     */
    public function setDestinataire(\Erazr\Bundle\UserBundle\Entity\User $destinataire)
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    /**
     * Get destinataire
     *
     * @return \Erazr\Bundle\UserBundle\Entity\User 
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set like
     *
     * @param string $like
     * @return Notification
     */
    public function setLike($like)
    {
        $this->like = $like;

        return $this;
    }

    /**
     * Get like
     *
     * @return string 
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Set friend
     *
     * @param string $friend
     * @return Notification
     */
    public function setFriend($friend)
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * Get friend
     *
     * @return string 
     */
    public function getFriend()
    {
        return $this->friend;
    }

    /**
     * Set comment
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Comment $comment
     * @return Notification
     */
    public function setComment(\Erazr\Bundle\SiteBundle\Entity\Comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Erazr\Bundle\SiteBundle\Entity\Comment 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set post
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Post $post
     * @return Notification
     */
    public function setPost(\Erazr\Bundle\SiteBundle\Entity\Post $post = null)
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

    /**
     * Add notifications
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Notification $notifications
     * @return Notification
     */
    public function addNotification(\Erazr\Bundle\SiteBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;

        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Notification $notifications
     */
    public function removeNotification(\Erazr\Bundle\SiteBundle\Entity\Notification $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
}
