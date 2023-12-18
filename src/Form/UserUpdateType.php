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
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
        ])
        
        ->add('profilePictureFilename', FileType::class, [
                'label' => 'Image de profil (JPEG/PNG)',
                'required' => false,
                'mapped' => false, // Ce champ n'est pas directement mappé sur l'attribut de l'entité
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG/PNG).',
                    ]),
                ],
            ])
             ->add('coverPictureFilename', FileType::class, [
                'label' => 'Image de profil (JPEG/PNG)',
                'required' => false,
                'mapped' => false, // Ce champ n'est pas directement mappé sur l'attribut de l'entité
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG/PNG).',
                    ]),
                ],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}