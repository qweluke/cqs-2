<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
        $user = new DeleteUserCommand();
        $user->id = 1;

        $userEntity = new User();

        $repositoryMock = m::mock(UserRepository::class);
        $repositoryMock->shouldReceive('find')->withArgs([1])->andReturn($userEntity);

        $emMock=m::mock(EntityManager::class);
        $emMock->shouldReceive('getRepository')->withArgs([User::class])->andReturn($repositoryMock);
        $emMock->shouldReceive('remove')->withArgs([$userEntity])->once();
        $emMock->shouldReceive('flush')->withAnyArgs()->once();

        $productHandler = new DeleteUserHandler($emMock);
        $productHandler->handle($user);
    }

    /**
     * FIAL test ;-)
     *
     * @expectedException AppBundle\Exception\InvalidUserException
     */
    public function testDeleteNotFoundUser()
    {
        $user = new DeleteUserCommand();
        $user->id = 1;

        $repositoryMock = m::mock(UserRepository::class);
        $repositoryMock->shouldReceive('find')->withArgs([1])->andReturnNull();

        $emMock=m::mock(EntityManager::class);
        $emMock->shouldReceive('getRepository')->withArgs([User::class])->andReturn($repositoryMock);
        $emMock->shouldNotReceive('remove');
        $emMock->shouldNotReceive('flush');

        $productHandler = new DeleteUserHandler($emMock);
        $productHandler->handle($user);
    }

    public function tearDown()
    {
        m::close();
    }


}
