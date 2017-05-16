<?php

namespace AppBundle\User\Handler;

use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Exception\InvalidUserException;
use AppBundle\User\Command\CreateUserCommand;
use AppBundle\User\Command\DeleteUserCommand;
use AppBundle\User\Command\EditUserCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DeleteUserHandler
{

    /**
     * @var EntityManager
     */
    private $em;


    /**
     * NewProductHandler constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param DeleteUserCommand $userCommand
     * @return bool|int
     */
    public function handle(DeleteUserCommand $userCommand)
    {
        $user = $this->em->getRepository(User::class)->find($userCommand->id);

        if(!$user) {
            throw new InvalidUserException();
        }

        try {
            $this->em->remove($user);
            $this->em->flush();
        } catch(\Exception $ex) {
            return false;
        }

        return true;
    }
}