<?php

namespace App\EducationBundle\Entity;

/**
 * Image
 */
class Image
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $descimage;

    /**
     * @var \App\EducationBundle\Entity\Product
     */
    private $product;


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
     * Set image
     *
     * @param string $image
     *
     * @return Image
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set descimage
     *
     * @param string $descimage
     *
     * @return Image
     */
    public function setDescimage($descimage)
    {
        $this->descimage = $descimage;

        return $this;
    }

    /**
     * Get descimage
     *
     * @return string
     */
    public function getDescimage()
    {
        return $this->descimage;
    }

    /**
     * Set product
     *
     * @param \App\EducationBundle\Entity\Product $product
     *
     * @return Image
     */
    public function setProduct(\App\EducationBundle\Entity\Product $product = null)
    {

        $this->product = $product;
    }

    /**
     * Get product
     *
     * @return \App\EducationBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
