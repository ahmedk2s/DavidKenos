<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\ChocolateShop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('email')
            ->add('job_title', TextType::class, [
                'attr' => [
                    'minlength' => '2',
                    'maxlength' => '255',
                ],
                'label' => 'Poste occupé',
            ])
            ->add('description')
            ->add('chocolate_shop', EntityType::class, [
                'class' => ChocolateShop::class,
                'choice_label' => 'city',
                'label' => 'Chocolaterie',
            ])
            ->add('profilePictureFilename', FileType::class, [
                'label' => 'Image de profil (JPEG/SVG)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG/SVG).',
                    ]),
                ],
            ])
            ->add('removeProfilePicture', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Supprimer',
            ])
            ->add('coverPictureFilename', FileType::class, [
                'label' => 'Image de couverture (JPEG/SVG)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG/SVG).',
                    ]),
                ],
            ])
            ->add('removeCoverPicture', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Supprimer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
