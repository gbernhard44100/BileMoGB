<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Customer;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * Phone
 *
 * @ORM\Table(name="phone")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhoneRepository")
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "gb_bilemo_phone_detail",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion({"PHONE_GET", "PHONE_DETAIL_GET"})
 * )
 * 
 */
class Phone
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"PHONE_GET", "PHONE_DETAIL_GET"})
     * 
     * 
     */
    private $id;

    /**
     * @var string
     * @Serializer\Groups({"PHONE_GET", "PHONE_DETAIL_GET"})
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     * @Serializer\Groups({"PHONE_DETAIL_GET"})
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * @var string
     * @Serializer\Groups({"PHONE_DETAIL_GET"})
     * @ORM\Column(name="screenSize", type="decimal", precision=3, scale=2)
     */
    private $screenSize;

    /**
     * @var int
     * @Serializer\Groups({"PHONE_DETAIL_GET"})
     * @ORM\Column(name="pictureResolution", type="smallint", nullable=true)
     */
    private $pictureResolution;

    /**
     * @var string
     * @Serializer\Groups({"PHONE_DETAIL_GET"})
     * @ORM\Column(name="processor", type="string", length=255)
     */
    private $processor;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Customer", mappedBy="phone")
     */
    private $customers;

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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add customer
     *
     * @param Customer $customer
     *
     * @return Phone
     */
    public function addCustomer(Customer $customer)
    {
        $this->customers[] = $customer;

        return $this;
    }

    /**
     * Remove customer
     *
     * @param Customer $customer
     */
    public function removeCustomer(Customer $customer)
    {
        $this->customers->removeElement($customer);
    }

    /**
     * Get customers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomers()
    {
        return $this->customers;
    }
}
