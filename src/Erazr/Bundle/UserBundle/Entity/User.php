<?php

namespace Erazr\Bundle\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Erazr\Bundle\SiteBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use P2\Bundle\RatchetBundle\WebSocket\Client\ClientInterface;


/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Erazr\Bundle\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser implements ClientInterface
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

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebookId;

    /**
     *
     * @ORM\Column(name="totalLike", type="integer", nullable=true)
     */
    private $totalLike;


    /**
     * @ORM\ManyToMany(targetEntity="Erazr\Bundle\UserBundle\Entity\User", mappedBy="myFriends")
     **/
    private $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="Erazr\Bundle\UserBundle\Entity\User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="Friends",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")}
     *      )
     **/
    private $myFriends;

    /**
     * @var string
     *
     * @ORM\Column(name="accessToken", type="text", nullable=true)
     */
    protected $accessToken;

    /**
     * @ORM\OneToMany(targetEntity="Erazr\Bundle\SiteBundle\Entity\Liking", mappedBy="user", cascade={"remove"})
     */
    private $likings;


    /**
    * Sets the websocket access token for this client
    *
    * @param string $accessToken
    * @return ClientInterface
    */
    public function setAccessToken($accessToken){
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
    * Returns the websocket access token for this client if any, or null.
    *
    * @return null|string
    */
    public function getAccessToken(){
        return $this->accessToken;
    }

    /**
    * Returns the array of public client data which will be transferred to the websocket client on successful
    * authentication. The websocket access token for this client should always be returned.
    *
    * @return array
    */
    public function jsonSerialize(){
        return array(
            'username' => $this->getUsername());
    }



    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
        $this->friendsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();
    }    

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
     * Set totalLike
     *
     * @param integer $totalLike
     * @return User
     */
    public function setTotalLike($totalLike)
    {
        $this->totalLike = $totalLike;

        return $this;
    }

    /**
     * Get totalLike
     *
     * @return integer 
     */
    public function getTotalLike()
    {
        return $this->totalLike;
    }

    /**
     * Add posts
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Post $posts
     * @return User
     */
    public function addPost(\Erazr\Bundle\SiteBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Post $posts
     */
    public function removePost(\Erazr\Bundle\SiteBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add comments
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Comment $comments
     * @return User
     */
    public function addComment(\Erazr\Bundle\SiteBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Comment $comments
     */
    public function removeComment(\Erazr\Bundle\SiteBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Add friendsWithMe
     *
     * @param \Erazr\Bundle\UserBundle\Entity\User $friendsWithMe
     * @return User
     */
    public function addFriendsWithMe(\Erazr\Bundle\UserBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe[] = $friendsWithMe;

        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param \Erazr\Bundle\UserBundle\Entity\User $friendsWithMe
     */
    public function removeFriendsWithMe(\Erazr\Bundle\UserBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe->removeElement($friendsWithMe);
    }

    /**
     * Get friendsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }

    /**
     * Add myFriends
     *
     * @param \Erazr\Bundle\UserBundle\Entity\User $myFriends
     * @return User
     */
    public function addMyFriend(\Erazr\Bundle\UserBundle\Entity\User $myFriends)
    {
        $this->myFriends[] = $myFriends;

        return $this;
    }

    /**
     * Remove myFriends
     *
     * @param \Erazr\Bundle\UserBundle\Entity\User $myFriends
     */
    public function removeMyFriend(\Erazr\Bundle\UserBundle\Entity\User $myFriends)
    {
        $this->myFriends->removeElement($myFriends);
    }

    /**
     * Get myFriends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMyFriends()
    {
        return $this->myFriends;
    }

    /**
     * Add likings
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Liking $likings
     * @return User
     */
    public function addLiking(\Erazr\Bundle\SiteBundle\Entity\Liking $likings)
    {
        $this->likings[] = $likings;

        return $this;
    }

    /**
     * Remove likings
     *
     * @param \Erazr\Bundle\SiteBundle\Entity\Liking $likings
     */
    public function removeLiking(\Erazr\Bundle\SiteBundle\Entity\Liking $likings)
    {
        $this->likings->removeElement($likings);
    }

    /**
     * Get likings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLikings()
    {
        return $this->likings;
    }
}
