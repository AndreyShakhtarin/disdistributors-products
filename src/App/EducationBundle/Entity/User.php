<?php

namespace App\EducationBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
/**
 * User
 */
class User extends BaseUser
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
        // your own logic
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
     * @return User
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $product;


    /**
     * Add product
     *
     * @param \App\EducationBundle\Entity\Product $product
     *
     * @return User
     */
    public function addProduct(\App\EducationBundle\Entity\Product $product)
    {
        $this->product[] = $product->setUser();

        return $this;
    }

    /**
     * Remove product
     *
     * @param \App\EducationBundle\Entity\Product $product
     */
    public function removeProduct(\App\EducationBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduct()
    {
        return $this->product;
    }
}
