<?php

namespace AppBundle\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

class EditUserCommand
{

    public function __construct(int $id, $name, $surname, $street, $houseNumber, $flatNumber, $city, $country, $zipCode, $phoneNumber)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->street = $street;
        $this->houseNumber = $houseNumber;
        $this->flatNumber = $flatNumber;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @var integer
     */
    public $id;

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