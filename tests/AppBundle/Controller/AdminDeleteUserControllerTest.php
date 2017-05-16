<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use AppBundle\User\Command\CreateUserCommand;
use AppBundle\User\Command\DeleteUserCommand;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\User\Handler\CreateUserHandler;
use AppBundle\User\Handler\DeleteUserHandler;
use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * Class AdminControllerTest
 * @package Tests\AppBundle\Controller
 */
class AdminDeleteUserControllerTest extends TestCase
{
    /**
     * Test success adding product
     */
    public function testDeleteSuccessUser()
    {
//        $user = new DeleteUserCommand();
//        $user->id = 1;
//
//        // Now, mock the repository so it returns the mock of the employee
//        $employeeRepository = $this
//            ->getMockBuilder(EntityRepository::class)
//            ->disableOriginalConstructor()
//            ->getMock();
//        $employeeRepository->expects($this->once())
//            ->method('find')
//            ->will($this->returnValue($user));
//
//        $entityManagerMock = m::mock('Doctrine\ORM\EntityManager');
//        $entityManagerMock->shouldReceive('getRepository')->withAnyArgs()->once()
//            ->withArgs(['find', $user->id]);
//
//
//
//        $productHandler = new DeleteUserHandler($entityManagerMock);
//        $productHandler->handle($user);
    }

    public function tearDown()
    {
        m::close();
    }


}
