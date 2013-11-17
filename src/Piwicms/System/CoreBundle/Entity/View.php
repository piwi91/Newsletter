<?php

namespace Piwicms\System\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * View
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Piwicms\System\CoreBundle\Repository\ViewRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class View
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
     * @var string
     *
     * @ORM\Column(name="view", type="text")
     */
    private $view;

    /**
     * @var string
     *
     * @ORM\Column(name="module", type="string", length=255)
     */
    private $module = 'page';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type = 'page';

    /**
     * @var string
     *
     * @ORM\Column(name="createdBy", type="string", length=255)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    private $modified;

    /**
     * @var ViewBlock
     *
     * @ORM\OneToMany(targetEntity="ViewBlock", mappedBy="view", cascade={"persist", "remove", "merge"})
     */
    private $viewBlock;

    function __construct()
    {
        $this->viewBlock = new ArrayCollection();
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
     * @return View
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
     * Set view
     *
     * @param string $view
     * @return View
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view
     *
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set module
     *
     * @param string $module
     * @return View
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return View
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     * @return View
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
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
     * @param \Piwicms\System\CoreBundle\Entity\ViewBlock $viewBlock
     */
    public function setViewBlock($viewBlock)
    {
        $this->viewBlock = $viewBlock;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Entity\ViewBlock
     */
    public function getViewBlock()
    {
        return $this->viewBlock;
    }

    /**
     * @param $viewBlock
     */
    public function addViewBlock(ViewBlock $viewBlock)
    {
        $this->viewBlock[] = $viewBlock;

        $viewBlock->setView($this);

        return $this;
    }

    /**
     * @param $viewBlock
     */
    public function removeViewBlock($viewBlock)
    {
        $this->viewBlock->removeElement($viewBlock);
    }
}
