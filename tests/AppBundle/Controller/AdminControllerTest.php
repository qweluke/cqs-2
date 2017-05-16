<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Products\Command\NewProductCommand;
use AppBundle\Products\Handler\NewProductHandler;
use AppBundle\User\Command\CreateUserCommand;
use AppBundle\User\Handler\CreateUserHandler;
use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * Class AdminControllerTest
 * @package Tests\AppBundle\Controller
 */
class AdminControllerTest extends TestCase
{
    /**
     * Test success adding product
     */
    public function testAddSuccessUser()
    {
        $user = new CreateUserCommand();
        $user->name = 'Łukasz';
        $user->surname = 'Malicki';
        $user->phoneNumber = '+48 733 974 114';
        $user->city = 'Katowice';
        $user->street = 'Murckowska';
        $user->zipCode = '40-881';
        $user->flatNumber = '12';
        $user->houseNumber = 12;
        $user->password = 'qweluke';

        $entityManagerMock = m::mock('Doctrine\ORM\EntityManager');
        $entityManagerMock
            ->shouldReceive('persist')->withArgs([$user])->once()
            ->with(m::on(function ($args) use ($user) {
                if (!($args instanceof User)) {
                    return false;
                }
                /**
                 * test User entity
                 */
                if ($args instanceof User &&
                    $args->getName() !== $user->name ||
                    $args->getSurname() !== $user->surname ||
                    $args->getEmail() !== $user->email ||
                    !empty($args->getFullAddress()) ||
                    !is_array($args->getRoles()) ||
                    !is_bool($args->getIsActive()) ||
                    !is_bool($args->isEnabled()) ||
                    !is_bool($args->getIsAccountNonExpired()) ||
                    !is_bool($args->getIsAccountNonLocked())
                ) {
                    return false;
                }

                /**
                 * test Profile entity
                 */
                if ($args->getProfile() instanceof Profile &&
                    $args->getProfile()->getCity() !== $user->city ||
                    $args->getProfile()->getStreet() !== $user->street ||
                    $args->getProfile()->getFlatNumber() !== $user->flatNumber ||
                    $args->getProfile()->getCountry() !== $user->country ||
                    $args->getProfile()->getZipCode() !== $user->zipCode ||
                    $args->getProfile()->getPhoneNumber() !== $user->phoneNumber ||
                    $args->getProfile()->getHouseNumber() !== $user->houseNumber ||
                    is_numeric($args->getProfile()->getId()) ||
                    (!$args->getProfile()->getUser() instanceof User)

                ) {
                    return false;
                }

                return true;
            }));

        $entityManagerMock->shouldReceive('flush')->once();

        $validatorMock = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface');

        $validatorMockCollection = m::mock('Symfony\Component\Validator\ConstraintViolationList');
        $validatorMockCollection->shouldReceive('count')->withNoArgs();

        $validatorMock->shouldReceive('validate')->withArgs([$user])->once()
            ->andReturn($validatorMockCollection);

        $passwordEncoderInterface = m::mock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
        $passwordEncoder = m::mock('Symfony\Component\Security\Core\Encoder\UserPasswordEncoder');

        $passwordEncoder->shouldReceive('encodePassword')->withAnyArgs()->once()
            ->andReturn($passwordEncoderInterface);

        $productHandler = new CreateUserHandler($entityManagerMock, $validatorMock, $passwordEncoder);
        $productHandler->handle($user);
    }

    /**
     * FIAL test ;-)
     *
     * @expectedException AppBundle\Exception\InvalidUserException
     */
    public function testAddFailUser()
    {
        $user = new CreateUserCommand();
        $user->name = 'Łukasz';
        $user->surname = 'Malicki';
        $user->phoneNumber = '+48 733 974 114';
        $user->city = 'Katowice';
        $user->street = 'Murckowska';
        $user->zipCode = '40-881';
        $user->flatNumber = '12';
        $user->houseNumber = 12;
        $user->password = 'qweluke';

        $entityManagerMock = m::mock('Doctrine\ORM\EntityManager');
        $entityManagerMock->shouldNotReceive('persist');
        $entityManagerMock->shouldNotReceive('flush');

        $validatorMock = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface');

        $validatorMockCollection = m::mock('Symfony\Component\Validator\ConstraintViolationList');
        $validatorMockCollection->shouldReceive('count')->withNoArgs()->andReturn(2);

        $validatorMock->shouldReceive('validate')->withArgs([$user])->once()
            ->andReturn($validatorMockCollection);

        $passwordEncoderInterface = m::mock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
        $passwordEncoder = m::mock('Symfony\Component\Security\Core\Encoder\UserPasswordEncoder');

        $passwordEncoder->shouldReceive('encodePassword')->withAnyArgs()
            ->andReturn($passwordEncoderInterface);

        $productHandler = new CreateUserHandler($entityManagerMock, $validatorMock, $passwordEncoder);
        $productHandler->handle($user);
    }

    public function tearDown()
    {
        m::close();
    }


}
