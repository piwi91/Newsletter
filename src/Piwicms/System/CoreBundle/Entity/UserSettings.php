<?php

namespace Piwicms\System\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSettings
 *
 * @ORM\Table(name="user_settings")
 * @ORM\Entity(repositoryClass="Piwicms\System\CoreBundle\Repository\UserSettingsRepository")
 */
class UserSettings
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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userSetting")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


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
     * Set user
     *
     * @param \stdClass $user
     * @return UserSettings
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set setting
     *
     * @param string $setting
     * @return UserSettings
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
     * @return UserSettings
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
