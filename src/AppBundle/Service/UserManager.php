<?php

namespace AppBundle\Service;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Łukasz Malicki
 */
class UserManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(EntityManager $entityManager, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    public function addUser(array $params)
    {
        $user = new User();
        $userProfile = new Profile();

        $user->setProfile($userProfile);
        $user->setName($params['name'])
            ->setSurname($params['surname']);

    }
}