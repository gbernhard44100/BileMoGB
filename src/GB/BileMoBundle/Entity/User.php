<?php

namespace GB\BileMoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="GB\BileMoBundle\Repository\UserRepository")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"GET_USERS", "GET_USER_DETAIL"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"GET_USERS", "GET_USER_DETAIL"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"GET_USERS", "GET_USER_DETAIL"})
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=255)
     * @Groups({"GET_USER_DETAIL"})
     */
    private $phoneNumber;

    /**
     * @var string
     * @ORM\Column(name="gender", type="string", length=1)
     * @Assert\Regex(
     *     pattern="/^(M|F)$/i",
     *     match=true,
     *     message="The gender has to be noted M (for Male) or F (for Female)"
     * )
     * @Groups({"GET_USER_DETAIL"})
     */
    private $gender;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Groups({"GET_USER_DETAIL"})
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="address", type="string", length=255)
     * @Groups({"GET_USER_DETAIL"})
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="GB\BileMoBundle\Entity\Store", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $store;
    
    /**
     * @ORM\ManyToOne(targetEntity="GB\BileMoBundle\Entity\Phone", inversedBy="users")
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return User
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
     * Set gender
     *
     * @param string $gender
     *
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
     * Set email
     *
     * @param string $email
     *
     * @return User
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
     * Set store
     *
     * @param \GB\BileMoBundle\Entity\Store $store
     *
     * @return User
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
     * @return User
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
