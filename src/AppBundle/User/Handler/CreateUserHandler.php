<?php

namespace AppBundle\User\Handler;

use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Exception\InvalidUserException;
use AppBundle\User\Command\CreateUserCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserHandler
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * NewProductHandler constructor.
     * @param EntityManager $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManager $entityManager, ValidatorInterface $validator, UserPasswordEncoder $passwordEncoder)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param CreateUserCommand $userCommand
     * @return bool|int
     */
    public function handle(CreateUserCommand $userCommand)
    {
        $productErrors = $this->validator->validate($userCommand);
        if ($productErrors->count()) {
            throw new InvalidUserException();
        }

        $user = new User();

        $user->setUsername($userCommand->username);
        $user->setName($userCommand->name);
        $user->setSurname($userCommand->surname)
            ->setEmail($userCommand->email);

        $user->setPassword($this->passwordEncoder->encodePassword($user, $userCommand->password));
        $user->setConfirmAccountSlug(md5(uniqid($user->getUsername(), true)));


        $user->setIsEnabled(true);
        $user->setIsActive(true);

        $userProfile = new Profile();
        $userProfile
            ->setCity($userCommand->city)
            ->setCountry($userCommand->country)
            ->setFlatNumber($userCommand->flatNumber)
            ->setHouseNumber($userCommand->houseNumber)
            ->setPhoneNumber($userCommand->phoneNumber)
            ->setStreet($userCommand->street)
            ->setZipCode($userCommand->zipCode);

        $user->setProfile($userProfile);

        $this->em->persist($user);
        $this->em->persist($userProfile);

        $this->em->flush();

        return $user->getId();
    }
}