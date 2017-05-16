<?php


namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Łukasz Malicki
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable, EquatableInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $surname;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Profile", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $profile;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="is_account_non_expired", type="boolean")
     */
    private $isAccountNonExpired;

    /**
     * @ORM\Column(name="is_account_non_locked", type="boolean")
     */
    private $isAccountNonLocked;

    /**
     * @ORM\Column(name="is_credentials_non_expired", type="boolean")
     */
    private $isCredentialsNonExpired;

    /**
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\Column(name="confirmation_token", type="string", nullable=true)
     */
    private $confirmationToken;

    /**
     * @ORM\Column(name="token_expired_date", type="datetime", nullable=true)
     */
    private $tokenExpiredDate;

    /**
     * @ORM\Column(name="confirm_account_slug", type="string", length=64, unique=true)
     */
    private $confirmAccountSlug;


    public function __construct()
    {
        $this->profile = new Profile();
        $this->roles = [self::ROLE_USER];
        $this->isActive = false;
        $this->isAccountNonExpired = true;
        $this->isAccountNonLocked = true;
        $this->isCredentialsNonExpired = true;
        $this->isEnabled = false;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }


    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            $this->isEnabled,
            $this->isAccountNonLocked,
            $this->isAccountNonExpired,
            $this->isCredentialsNonExpired
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            $this->isAccountNonLocked,
            $this->isAccountNonExpired,
            $this->isCredentialsNonExpired
            ) = unserialize($serialized);
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $isAccountNonExpired
     */
    public function setIsAccountNonExpired($isAccountNonExpired)
    {
        $this->isAccountNonExpired = $isAccountNonExpired;
    }

    /**
     * @param mixed $isAccountNonLocked
     */
    public function setIsAccountNonLocked($isAccountNonLocked)
    {
        $this->isAccountNonLocked = $isAccountNonLocked;
    }

    /**
     * @param mixed $isCredentialsNonExpired
     */
    public function setIsCredentialsNonExpired($isCredentialsNonExpired)
    {
        $this->isCredentialsNonExpired = $isCredentialsNonExpired;
    }

    /**
     * @param mixed $isEnabled
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    public function isAccountNonExpired()
    {
        return $this->isAccountNonExpired;
    }

    public function isAccountNonLocked()
    {
        return $this->isAccountNonLocked;
    }

    public function isCredentialsNonExpired()
    {
        return $this->isCredentialsNonExpired;
    }

    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @return mixed
     */
    public function getConfirmAccountSlug()
    {
        return $this->confirmAccountSlug;
    }

    /**
     * @param mixed $confirmAccountSlug
     */
    public function setConfirmAccountSlug($confirmAccountSlug)
    {
        $this->confirmAccountSlug = $confirmAccountSlug;
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
     * Get isAccountNonExpired
     *
     * @return boolean
     */
    public function getIsAccountNonExpired()
    {
        return $this->isAccountNonExpired;
    }

    /**
     * Get isAccountNonLocked
     *
     * @return boolean
     */
    public function getIsAccountNonLocked()
    {
        return $this->isAccountNonLocked;
    }

    /**
     * Get isCredentialsNonExpired
     *
     * @return boolean
     */
    public function getIsCredentialsNonExpired()
    {
        return $this->isCredentialsNonExpired;
    }

    /**
     * Get isEnabled
     *
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Set profile
     *
     * @param Profile $profile
     *
     * @return User
     */
    public function setProfile(Profile $profile)
    {
        $profile->setUser($this);
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \AppBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set confirmationToken
     *
     * @param string $confirmationToken
     *
     * @return User
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * Get confirmationToken
     *
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Set tokenExpiredDate
     *
     * @param \DateTime $tokenExpiredDate
     *
     * @return User
     */
    public function setTokenExpiredDate($tokenExpiredDate)
    {
        $this->tokenExpiredDate = $tokenExpiredDate;

        return $this;
    }

    /**
     * Get tokenExpiredDate
     *
     * @return \DateTime
     */
    public function getTokenExpiredDate()
    {
        return $this->tokenExpiredDate;
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
     * Set surname
     *
     * @param string $surname
     *
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
     * Returns formatted address
     * @return string
     */
    public function getFullAddress(): string
    {
        $str = '';
//        $str = $this->getProfile()->getStreet() . ' ' . $this->getProfile()->getHouseNumber() . ' ' . $this->getProfile()->getFlatNumber() . PHP_EOL;
//        $str .= $this->getProfile()->getCity() . ', ' . $this->getProfile()->getZipCode();
//        $str .= !empty($this->getProfile()->getCountry()) ? PHP_EOL . $this->getProfile()->getCountry() : '';

        return $str;
    }
}
