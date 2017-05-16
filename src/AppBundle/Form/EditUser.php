<?php

namespace AppBundle\Form;

use AppBundle\User\Command\CreateUserCommand;
use AppBundle\User\Command\EditUserCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class User
 */
class EditUser extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'form_user.name',
                'required' => true
            ])
            ->add('surname', TextType::class, [
                'label' => 'form_user.surname',
                'required' => true
            ])
            ->add('street', TextType::class, [
                'label' => 'form_user_profile.street',
                'required' => false
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'form_user_profile.zip_code',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'form_user_profile.city',
                'required' => false
            ])
            ->add('houseNumber', TextType::class, [
                'label' => 'form_user_profile.house_number',
                'required' => false
            ])
            ->add('flatNumber', TextType::class, [
                'label' => 'form_user_profile.flat_number',
                'required' => false
            ])
            ->add('country', TextType::class, [
                'label' => 'form_user_profile.country',
                'required' => false
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'form_user_profile.phone_number',
                'required' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'form_user',
            'data_class' => EditUserCommand::class
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'user';
    }
}