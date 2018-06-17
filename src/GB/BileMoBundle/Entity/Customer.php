<?php

namespace GB\BileMoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="GB\BileMoBundle\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"GET_CUSTOMERS", "GET_CUSTOMER_DETAIL"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank
     * @Serializer\Groups({"GET_CUSTOMERS", "GET_CUSTOMER_DETAIL"})
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank
     * @Serializer\Groups({"GET_CUSTOMERS", "GET_CUSTOMER_DETAIL"})
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="phonenumber", type="string", length=255)
     * @Serializer\Groups({"GET_CUSTOMER_DETAIL"})
     */
    private $phonenumber;

    /**
     * @var string
     * @ORM\Column(name="gender", type="string", length=1)
     * @Assert\Regex(
     *     pattern="/^(M|F)$/i",
     *     match=true,
     *     message="The gender has to be noted M (for Male) or F (for Female)"
     * )
     * @Serializer\Groups({"GET_CUSTOMER_DETAIL"})
     */
    private $gender;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email(message = "'{{ value }}' is not a valid email.")
     * @Serializer\Groups({"GET_CUSTOMER_DETAIL"})
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="address", type="string", length=255)
     * @Serializer\Groups({"GET_CUSTOMER_DETAIL"})
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="GB\BileMoBundle\Entity\Store", inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $store;
    
    /**
     * @ORM\ManyToOne(targetEntity="GB\BileMoBundle\Entity\Phone", inversedBy="customers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $phone;

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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Customer
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
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Customer
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phonenumber
     *
     * @return Customer
     */
    public function setPhonenumber($phonenumber)
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhonenumber()
    {
        return $this->phonenumber;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Customer
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
     * Set email
     *
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Customer
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
     * Set store
     *
     * @param \GB\BileMoBundle\Entity\Store $store
     *
     * @return Customer
     */
    public function setStore(\GB\BileMoBundle\Entity\Store $store)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store
     *
     * @return \GB\BileMoBundle\Entity\Store
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Set phone
     *
     * @param \GB\BileMoBundle\Entity\Phone $phone
     *
     * @return Customer
     */
    public function setPhone(\GB\BileMoBundle\Entity\Phone $phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return \GB\BileMoBundle\Entity\Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
