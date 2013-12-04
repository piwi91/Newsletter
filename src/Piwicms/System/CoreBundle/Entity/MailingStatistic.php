<?php

namespace Piwicms\System\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailingStatistics
 *
 * @ORM\Table(name="mailing_statistic")
 * @ORM\Entity(repositoryClass="Piwicms\System\CoreBundle\Repository\MailingStatisticRepository")
 */
class MailingStatistic
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
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type = 'url';

    /**
     * @var Mailing
     *
     * @ORM\ManyToOne(targetEntity="Mailing", cascade={"remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mailing_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $mailing;

    /**
     * @var MailingUser
     *
     * @ORM\ManyToOne(targetEntity="MailingUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mailinglist_user_id", referencedColumnName="id")
     * })
     */
    private $mailingListUser;


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
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return MailingStatistics
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    
        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime 
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return MailingStatistics
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Piwicms\System\CoreBundle\Entity\Mailing $mailing
     */
    public function setMailing($mailing)
    {
        $this->mailing = $mailing;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Entity\Mailing
     */
    public function getMailing()
    {
        return $this->mailing;
    }

    /**
     * @param \Piwicms\System\CoreBundle\Entity\MailingUser $mailingListUser
     */
    public function setMailingUser($mailingListUser)
    {
        $this->mailingListUser = $mailingListUser;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Entity\MailingUser
     */
    public function getMailingUser()
    {
        return $this->mailingListUser;
    }
}
