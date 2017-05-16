<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Product;
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
            ->shouldReceive('persist')->withArgs([$user])->twice()
            ->with(m::on(function ($args) use ($user) {
                if (!($args instanceof User)) {
                    return false;
                }
                if ($args->getName() !== $user->name ||
                    $args->getSurname() !== $user->surname ||
                    $args->getProfile()->getCity() !== $user->city ||
                    $args->getProfile()->getPhoneNumber() !== $user->phoneNumber
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
        $passwordEncoder->shouldReceive('encodePassword')->once()->withArgs([$user, $user->password])->andReturn($passwordEncoder);

        $productHandler = new CreateUserHandler($entityManagerMock, $validatorMock, $passwordEncoder);
        $productHandler->handle($user);
    }
//
//    /**
//     * @expectedException AppBundle\Exception\InvalidProductException
//     */
//    public function testAddFailProduct()
//    {
//        $product = new NewProductCommand();
//        $product->name = 'Product name!';
//        $product->description = 'Hi, I want to sell my new boat. It is my treasure so I will send it only to ppl that will take care about her! Only cash, no credits cards or coupons!';
//        $product->price = 141.12;
//
//        $entityManagerMock = m::mock('Doctrine\ORM\EntityManager');
//        $entityManagerMock->shouldNotReceive('persist');
//        $entityManagerMock->shouldNotReceive('flush');
//
//        $validatorMock = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface');
//
//        $validatorMockCollection = m::mock('Symfony\Component\Validator\ConstraintViolationList');
//        $validatorMockCollection->shouldReceive('count')->withNoArgs()->andReturn(2);
//
//        $validatorMock->shouldReceive('validate')->withArgs([$product])->once()
//            ->andReturn($validatorMockCollection);
//
//        $productNotificationSenderMock = m::mock('AppBundle\Products\NewProductNotificationSender');
//        $productNotificationSenderMock->shouldNotReceive('sendNewProductEmail');
//        $productHandler = new NewProductHandler($entityManagerMock, $validatorMock, $productNotificationSenderMock);
//        $productHandler->handle($product);
//    }

    public function tearDown()
    {
        m::close();
    }


}
