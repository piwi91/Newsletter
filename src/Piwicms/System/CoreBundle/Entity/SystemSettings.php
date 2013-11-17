<?php

namespace Piwicms\System\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemSettings
 *
 * @ORM\Table(name="system_settings")
 * @ORM\Entity(repositoryClass="Piwicms\System\CoreBundle\Repository\SystemSettingsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SystemSettings
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
     * @ORM\Column(name="module", type="string", length=255)
     */
    private $module;

    /**
     * @var string
     *
     * @ORM\Column(name="setting", type="string", length=255)
     */
    private $setting;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="field_type", type="string", length=255)
     */
    private $fieldType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    private $modified;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modified_by", referencedColumnName="id")
     * })
     */
    private $modifiedBy;

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
     * Set module
     *
     * @param string $module
     * @return SystemSettings
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
     * Set setting
     *
     * @param string $setting
     * @return SystemSettings
     */
    public function setSetting($setting)
    {
        $this->setting = $setting;
    
        return $this;
    }

    /**
     * Get setting
     *
     * @return string 
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return SystemSettings
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
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
     * @return \User
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * @param \User $modifiedBy
     */
    public function setModifiedBy(User $modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * @return string
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * @param string $fieldType
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;
    }
}
