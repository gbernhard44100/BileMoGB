<?php

namespace GB\BileMoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone
 *
 * @ORM\Table(name="phone")
 * @ORM\Entity(repositoryClass="GB\BileMoBundle\Repository\PhoneRepository")
 */
class Phone
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="screenSize", type="decimal", precision=3, scale=2)
     */
    private $screenSize;

    /**
     * @var int
     *
     * @ORM\Column(name="pictureResolution", type="smallint", nullable=true)
     */
    private $pictureResolution;

    /**
     * @var string
     *
     * @ORM\Column(name="processor", type="string", length=255)
     */
    private $processor;


    /**
     * Get id
     *
     * @return int
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
     * @return Phone
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
     * Set brand
     *
     * @param string $brand
     *
     * @return Phone
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set screenSize
     *
     * @param string $screenSize
     *
     * @return Phone
     */
    public function setScreenSize($screenSize)
    {
        $this->screenSize = $screenSize;

        return $this;
    }

    /**
     * Get screenSize
     *
     * @return string
     */
    public function getScreenSize()
    {
        return $this->screenSize;
    }

    /**
     * Set pictureResolution
     *
     * @param integer $pictureResolution
     *
     * @return Phone
     */
    public function setPictureResolution($pictureResolution)
    {
        $this->pictureResolution = $pictureResolution;

        return $this;
    }

    /**
     * Get pictureResolution
     *
     * @return int
     */
    public function getPictureResolution()
    {
        return $this->pictureResolution;
    }

    /**
     * Set processor
     *
     * @param string $processor
     *
     * @return Phone
     */
    public function setProcessor($processor)
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * Get processor
     *
     * @return string
     */
    public function getProcessor()
    {
        return $this->processor;
    }
}
