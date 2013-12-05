<?php

namespace Piwicms\System\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MailingList
 *
 * @ORM\Table(name="mailinglist")
 * @ORM\Entity(repositoryClass="Piwicms\System\CoreBundle\Repository\MailingListRepository")
 */
class MailingList
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var MailingUser
     *
     * @ORM\ManyToMany(targetEntity="MailingUser", mappedBy="mailingList")
     */
    private $mailingUser;

    function __construct()
    {
        $this->mailingUser = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return MailingList
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Piwicms\System\CoreBundle\Entity\Mailing $mailingUser
     */
    public function setMailingUser($mailingUser)
    {
        $this->mailingUser = $mailingUser;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Entity\Mailing
     */
    public function getMailingUser()
    {
        return $this->mailingUser;
    }
}
