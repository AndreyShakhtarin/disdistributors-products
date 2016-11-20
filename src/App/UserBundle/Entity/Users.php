<?php

namespace App\UserBundle\Entity;


use FOS\UserBundle\Model\User as BaseUser;
/**
 * Users
 */
class Users extends BaseUser
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    private $name;


    public function __construct()
    {
        parent::__construct();
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
     *
     * @return Users
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
     * @var string
     */
    private $photoMerchandiser;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $adress;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $company;

    /**
     * @var string
     */
    private $logoCompany;

    /**
     * @var \DateTime
     */
    private $birthdayCompany;

    /**
     * @var boolean
     */
    private $merchandiser;


    /**
     * Set photoMerchandiser
     *
     * @param string $photoMerchandiser
     *
     * @return Users
     */
    public function setPhotoMerchandiser($photoMerchandiser)
    {
        $this->photoMerchandiser = $photoMerchandiser;

        return $this;
    }

    /**
     * Get photoMerchandiser
     *
     * @return string
     */
    public function getPhotoMerchandiser()
    {
        return $this->photoMerchandiser;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Users
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
     * Set middleName
     *
     * @param string $middleName
     *
     * @return Users
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Users
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Users
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Users
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
     * Set city
     *
     * @param string $city
     *
     * @return Users
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
     * Set adress
     *
     * @param string $adress
     *
     * @return Users
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Users
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return Users
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
     * Set logoCompany
     *
     * @param string $logoCompany
     *
     * @return Users
     */
    public function setLogoCompany($logoCompany)
    {
        $this->logoCompany = $logoCompany;

        return $this;
    }

    /**
     * Get logoCompany
     *
     * @return string
     */
    public function getLogoCompany()
    {
        return $this->logoCompany;
    }

    /**
     * Set birthdayCompany
     *
     * @param \DateTime $birthdayCompany
     *
     * @return Users
     */
    public function setBirthdayCompany($birthdayCompany)
    {
        $this->birthdayCompany = $birthdayCompany;

        return $this;
    }

    /**
     * Get birthdayCompany
     *
     * @return \DateTime
     */
    public function getBirthdayCompany()
    {
        return $this->birthdayCompany;
    }

    /**
     * Set merchandiser
     *
     * @param boolean $merchandiser
     *
     * @return Users
     */
    public function setMerchandiser($merchandiser)
    {
        $this->merchandiser = $merchandiser;

        return $this;
    }

    /**
     * Get merchandiser
     *
     * @return boolean
     */
    public function getMerchandiser()
    {
        return $this->merchandiser;
    }

    public static function getMerchandiseris()
    {
        return[
            'client' => false,
            'merchandiser' => true,
        ];
    }

    public static function getValueMerchandiseris($merchandiser)
    {
        $position = self::getMerchandiseris();
        return $position[$merchandiser];
    }
}
