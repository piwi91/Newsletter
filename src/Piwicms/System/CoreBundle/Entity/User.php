<?php

namespace Piwicms\System\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Piwicms\System\CoreBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
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
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="middlename", type="string", length=255, nullable=true)
     */
    protected $middlename;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    protected $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255)
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=255)
     */
    protected $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_phone", type="string", length=255, nullable=true)
     */
    protected $mobilePhone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    protected $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     */
    protected $company;

    /**
     * @var string
     *
     * @ORM\Column(name="company_vat_number", type="string", length=255, nullable=true)
     */
    protected $companyVatNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="receive_newsletter", type="boolean")
     */
    protected $receiveNewsletter = true;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    protected $comment;

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
     * @ORM\ManyToMany(targetEntity="Piwicms\System\CoreBundle\Entity\Group")
     * @ORM\JoinTable(name="user_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var PrivateMessage
     *
     * @ORM\OneToMany(targetEntity="PrivateMessage", mappedBy="fromUser")
     */
    private $privateMessageSend;

    /**
     * @var PrivateMessage
     *
     * @ORM\OneToMany(targetEntity="PrivateMessage", mappedBy="toUser")
     */
    private $privateMessageReceived;

    /**
     * @var UserSettings
     *
     * @ORM\OneToMany(targetEntity="UserSettings", mappedBy="user")
     */
    private $userSetting;

    public function __construct()
    {
        parent::__construct();
        $this->userSettings = new ArrayCollection();
        $this->privateMessageSend = new ArrayCollection();
        $this->privateMessageReceived = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->firstname . " " . $this->middlename . " " . $this->surname;
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
     * @return User
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
     * Set middlename
     *
     * @param string $middlename
     * @return User
     */
    public function setMiddlename($middlename)
    {
        $this->middlename = $middlename;
    
        return $this;
    }

    /**
     * Get middlename
     *
     * @return string 
     */
    public function getMiddlename()
    {
        return $this->middlename;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
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
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return User
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    
        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set mobilePhone
     *
     * @param string $mobilePhone
     * @return User
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;
    
        return $this;
    }

    /**
     * Get mobilePhone
     *
     * @return string 
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return User
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    
        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;
    
        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set companyVatNumber
     *
     * @param string $companyVatNumber
     * @return User
     */
    public function setCompanyVatNumber($companyVatNumber)
    {
        $this->companyVatNumber = $companyVatNumber;
    
        return $this;
    }

    /**
     * Get companyVatNumber
     *
     * @return string 
     */
    public function getCompanyVatNumber()
    {
        return $this->companyVatNumber;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set receiveNewsletter
     *
     * @param boolean $receiveNewsletter
     * @return User
     */
    public function setReceiveNewsletter($receiveNewsletter)
    {
        $this->receiveNewsletter = $receiveNewsletter;
    
        return $this;
    }

    /**
     * Get receiveNewsletter
     *
     * @return boolean 
     */
    public function getReceiveNewsletter()
    {
        return $this->receiveNewsletter;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return User
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return \UserSettings
     */
    public function getUserSettings()
    {
        return $this->userSetting;
    }

    /**
     * @param UserSettings $userSetting
     * @return $this
     */
    public function addUserSetting(UserSettings $userSetting)
    {
        $this->userSetting[] = $userSetting;
        $userSetting->setUser($this);
        return $this;
    }

    /**
     * @param UserSettings $userSetting
     * @return $this
     */
    public function removeUserSetting(UserSettings $userSetting)
    {
        $this->getUserSettings()->removeElement($userSetting);
        return $this;
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
     * @return \Piwicms\System\CoreBundle\Repository\PrivateMessage
     */
    public function getPrivateMessageReceived()
    {
        return $this->privateMessageReceived;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Repository\PrivateMessage
     */
    public function getPrivateMessageSend()
    {
        return $this->privateMessageSend;
    }
}
