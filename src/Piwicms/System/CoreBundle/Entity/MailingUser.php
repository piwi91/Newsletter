<?php

namespace Piwicms\System\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MailingUser
 *
 * @ORM\Table(name="mailinguser")
 * @ORM\Entity(repositoryClass="Piwicms\System\CoreBundle\Repository\MailingUserRepository")
 */
class MailingUser
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
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="emailaddress", type="string", length=255)
     */
    private $emailaddress;

    /**
     * @var MailingList
     *
     * @ORM\ManyToMany(targetEntity="MailingList", inversedBy="mailingUser")
     * @ORM\JoinTable(name="mailinguser_mailinglist",
     *   joinColumns={@ORM\JoinColumn(name="mailinguser_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="mailinglist_id", referencedColumnName="id")}
     * )
     */
    private $mailingList;

    public function __construct()
    {
        $this->mailingList = new ArrayCollection();
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
     * Set firstname
     *
     * @param string $firstname
     * @return MailingUser
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return MailingUser
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set emailaddress
     *
     * @param string $emailaddress
     * @return MailingUser
     */
    public function setEmailaddress($emailaddress)
    {
        $this->emailaddress = $emailaddress;
    
        return $this;
    }

    /**
     * Get emailaddress
     *
     * @return string 
     */
    public function getEmailaddress()
    {
        return $this->emailaddress;
    }

    /**
     * @param \Piwicms\System\CoreBundle\Entity\MailingList $mailingList
     */
    public function setMailingList($mailingList)
    {
        $this->mailingList = $mailingList;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Entity\MailingList
     */
    public function getMailingList()
    {
        return $this->mailingList;
    }
}
