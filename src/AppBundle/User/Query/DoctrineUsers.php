<?php

namespace AppBundle\User\Query;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class DoctrineUsers
{
    /**
     * @var EntityManager
     */
    private $em;


    /**
     * DoctrineProducts constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Returns all users
     */
    public function getAll()
    {
        return $this->em->getRepository(User::class)->findAll();
    }
}
