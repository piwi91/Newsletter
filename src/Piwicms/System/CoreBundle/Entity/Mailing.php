<?php

namespace Piwicms\System\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Mailing
 *
 * @ORM\Table(name="mailing")
 * @ORM\Entity(repositoryClass="Piwicms\System\CoreBundle\Repository\MailingRepository")
 */
class Mailing
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var bool
     *
     * @ORM\Column(name="send", type="string", nullable=false)
     */
    private $status = '-';

    /**
     * @var string
     *
     * @ORM\Column(name="created_by", type="string", length=255)
     */
    private $createdBy;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count = 0;

    /**
     * @var View
     *
     * @ORM\ManyToOne(targetEntity="View")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="template_id", referencedColumnName="id")
     * })
     */
    private $template;

    /**
     * @var MailingList
     *
     * @ORM\ManyToMany(targetEntity="MailingList")
     * @ORM\JoinTable(name="mailing_mailinglist",
     *   joinColumns={@ORM\JoinColumn(name="mailing_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="mailinglist_id", referencedColumnName="id")}
     * )
     */
    private $mailingList;

    /**
     * @var MailingBlock
     *
     * @ORM\OneToMany(targetEntity="MailingBlock", mappedBy="mailing", cascade={"persist", "remove"})
     */
    private $mailingBlock;

    public function __construct()
    {
        $this->mailingList = new ArrayCollection();
        $this->mailingBlock = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Mailing
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Mailing
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
     * @param \Piwicms\System\CoreBundle\Entity\View $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Entity\View
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
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

    /**
     * @param \Piwicms\System\CoreBundle\Entity\MailingBlock $mailingBlock
     */
    public function setMailingBlock($mailingBlock)
    {
        $this->mailingBlock = $mailingBlock;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Entity\MailingBlock
     */
    public function getMailingBlock()
    {
        return $this->mailingBlock;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }
}
