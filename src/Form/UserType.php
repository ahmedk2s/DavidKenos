<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('email')
            ->add('password')
            ->add('roles')
            ->add('job_title')
            ->add('description')
            ->add('profile_picture')
            ->add('cover_picture')
            ->add('facebook_link')
            ->add('twitter_link')
            ->add('instagram_link')
            ->add('linkedin_link')
            ->add('chocolate_shop')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
