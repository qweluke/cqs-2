<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Exception\InvalidUserException;
use AppBundle\Form;
use AppBundle\User\Command\CreateUserCommand;
use AppBundle\User\Command\DeleteUserCommand;
use AppBundle\User\Command\EditUserCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package AppBundle\Controller\Admin
 *
 * @Route("/admin")
 */
class UserController extends Controller
{
    /**
     * @Route("/new-user")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $object = new CreateUserCommand();
        $formProduct = $this->createForm(Form\NewUser::class, $object);
        $formProduct->handleRequest($request);

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            try {
                $this->get('user_new_handler')->handle($formProduct->getData());
                return $this->redirectToRoute('homepage');
            } catch (InvalidUserException $ex) {
                $this->get('session')->getFlashBag()->add('warning', $formProduct->getErrors(true, true));
            }
        }

        return $this->render(':admin:userForm.html.twig', [
            'formProduct' => $formProduct->createView()
        ]);
    }

    /**
     * @Route("/user/{id}/edit")
     *
     * @param Request $request
     * @param User $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $id)
    {
        /**
         * @var Profile
         */
        $userProfile = $id->getProfile();

        $object = new EditUserCommand(
            $id->getId(),
            $id->getName(),
            $id->getSurname(),
            $userProfile->getStreet(),
            $userProfile->getHouseNumber(),
            $userProfile->getFlatNumber(),
            $userProfile->getCity(),
            $userProfile->getCountry(),
            $userProfile->getZipCode(),
            $userProfile->getPhoneNumber()
        );

        $formProduct = $this->createForm(Form\EditUser::class, $object);
        $formProduct->handleRequest($request);

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            try {
                $this->get('user_edit_handler')->handle($formProduct->getData());
                $this->get('session')->getFlashBag()->add('success', 'User edited');
                return $this->redirectToRoute('homepage');
            } catch (InvalidUserException $ex) {
                $this->get('session')->getFlashBag()->add('warning', $formProduct->getErrors(true, true));
            }
        }

        return $this->render(':admin:userForm.html.twig', [
            'formProduct' => $formProduct->createView()
        ]);
    }

    /**
     * @Route("/user/{id}/delete")
     *
     * @param User $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Request $request
     */
    public function deleteAction(User $id)
    {
        $object = new DeleteUserCommand();
        $object->id = $id->getId();

        $state = $this->get('user_delete_handler')->handle($object);

        if ($state) {
            $this->get('session')->getFlashBag()->add('success', 'User deleted');
        } else {
            $this->get('session')->getFlashBag()->add('warning', 'User NOT deleted');
        }

        return $this->redirectToRoute('homepage');
    }
}
