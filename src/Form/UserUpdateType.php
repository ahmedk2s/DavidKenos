<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\ChocolateShop;


class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('last_name', TextType::class, [
            'attr' => [
                'minlength' => '2',
                'maxlength' => '100',
            ],
            'label' => 'Nom',
            'constraints' => [
                new Assert\NotBlank(),
                new Length(['min' => 2, 'max' => 100]),
            ],
        ])
        ->add('first_name', TextType::class, [
            'attr' => [
                'minlength' => '2',
                'maxlength' => '100',
            ],
            'label' => 'Prénom',
            'constraints' => [
                new Assert\NotBlank(),
                new Length(['min' => 2, 'max' => 100]),
            ],
        ])
        ->add('job_title', TextType::class, [
            'attr' => [
                'minlength' => '2',
                'maxlength' => '255',
            ],
            'label' => 'Poste occupé',
        ])
        ->add('chocolate_shop', EntityType::class, [
            'class' => ChocolateShop::class,
            'choice_label' => 'city',
            'label' => 'Chocolaterie',
        ]);
        
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}