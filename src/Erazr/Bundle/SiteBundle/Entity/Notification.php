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
     * @var array
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;


    /**
     * @ORM\ManyToOne(targetEntity="Erazr\Bundle\UserBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="expediteur_id", referencedColumnName="id", nullable=false)
     */
    private $expediteur;

    /**
     * @ORM\ManyToOne(targetEntity="Erazr\Bundle\UserBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="destinataire_id", referencedColumnName="id", nullable=false)
     **/
    private $destinataire;

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
     * Set type
     *
     * @param array $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return array 
     */
    public function getType()
    {
        return $this->type;
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
}
