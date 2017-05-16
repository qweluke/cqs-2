<?php

namespace AppBundle\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserCommand
{

    /**
     * @Assert\NotBlank(message="Please enter a username")
     * @Assert\Length(max="64", min="3")
     * @Assert\Regex(pattern="/^(?=.*[A-Z\s]).+/", match=false, message="Invalid username")
     */
    public $username;

    /**
     * @Assert\NotBlank(message="Please enter a password")
     * @Assert\Length(max="255", min="5")
     */
    public $password;

    /**
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter user name.")
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter user surname.")
     */
    public $surname;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter street.")
     */
    public $street;

    /**
     * @var string
     */
    public $houseNumber;

    /**
     * @var string
     */
    public $flatNumber;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter City.")
     */
    public $city;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $zipCode;

    /**
     * @var string
     */
    public $phoneNumber;

}