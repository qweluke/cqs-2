<?php

namespace AppBundle\User\Handler;

use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Exception\InvalidUserException;
use AppBundle\User\Command\CreateUserCommand;
use AppBundle\User\Command\EditUserCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditUserHandler
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
     * NewProductHandler constructor.
     * @param EntityManager $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManager $entityManager, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param CreateUserCommand $userCommand
     * @return bool|int
     */
    public function handle(EditUserCommand $userCommand)
    {
        $productErrors = $this->validator->validate($userCommand);
        if ($productErrors->count()) {
            throw new InvalidUserException();
        }

        $user = $this->em->getRepository(User::class)->find($userCommand->id);

        $user->setName($userCommand->name);
        $user->setSurname($userCommand->surname);

        $userProfile = $user->getProfile();
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