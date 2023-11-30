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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\ChocolateShop;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\Email(),
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 255]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'Adresse e-mail invalide',
                    ]),
                ],
                'label' => 'Adresse email',
            ])
           ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a password']),
                    new Length([
                        'min' => 16,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]{16,}$/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre, un caractère spécial et avoir au moins 16 caractères.',
                    ]),
                ],
                'label' => 'Mot de Passe',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Employé' => 'ROLE_EMPLOYE',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Rôles',
            ]);
            // ->add('profilePictureFilename', FileType::class, [
            //     'label' => 'Image de profil (JPEG/PNG)',
            //     'required' => false,
            //     'mapped' => false, // Ce champ n'est pas directement mappé sur l'attribut de l'entité
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '1024k',
            //             'mimeTypes' => [
            //                 'image/jpeg',
            //                 'image/png',
            //             ],
            //             'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG/PNG).',
            //         ]),
            //     ],
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

