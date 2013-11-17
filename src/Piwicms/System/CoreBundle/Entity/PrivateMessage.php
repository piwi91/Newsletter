<?php

namespace Piwicms\System\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrivateMessage
 *
 * @ORM\Table(name="private_message")
 * @ORM\Entity(repositoryClass="Piwicms\System\CoreBundle\Repository\PrivateMessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PrivateMessage
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
     * @ORM\Column(name="Title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="Text", type="text")
     */
    private $text;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Unread", type="boolean")
     */
    private $unread = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Modified", type="datetime")
     */
    private $modified;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="privateMessageSend")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_from", referencedColumnName="id")
     * })
     */
    private $fromUser;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="privateMessageReceived")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_to", referencedColumnName="id")
     * })
     */
    private $toUser;

    /**
     * @var PrivateMessage
     *
     * @ORM\ManyToOne(targetEntity="PrivateMessage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="replyOn", referencedColumnName="id")
     * })
     */
    private $replyOn;

    public function __construct()
    {
        $this->unread = true; // New task is always unread!
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
     * @return PrivateMessage
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
     * Set text
     *
     * @param string $text
     * @return PrivateMessage
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set user
     *
     * @param \stdClass $user
     * @return UserSettings
     */
    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set user
     *
     * @param \stdClass $user
     * @return UserSettings
     */
    public function setToUser($toUser)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return SystemSettings
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setModified()
    {
        $this->modified = new \DateTime();

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return SystemSettings
     * @ORM\PrePersist
     */
    public function setCreated()
    {
        $this->created = new \DateTime();

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
     * @return boolean
     */
    public function getUnread()
    {
        return $this->unread;
    }

    /**
     * @param boolean $unread
     */
    public function setUnread($unread)
    {
        $this->unread = $unread;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Repository\PrivateMessage
     */
    public function getReplyOn()
    {
        return $this->replyOn;
    }

    /**
     * @param \Piwicms\System\CoreBundle\Repository\PrivateMessage $replyOn
     */
    public function setReplyOn($replyOn)
    {
        $this->replyOn = $replyOn;
    }
}
