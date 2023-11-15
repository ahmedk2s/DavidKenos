<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfileEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('email')
            ->add('job_title')
            ->add('description')
            ->add('profilePictureFile', VichImageType::class, [
    'label' => 'Photo de profil (fichier image)',
    'mapped' => true, // Maintenant, le champ est directement lié à la propriété de l'entité
    'required' => false,
    'constraints' => [
        new File([
            'maxSize' => '1024k',
            'mimeTypes' => [
                'image/*',
            ],
            'mimeTypesMessage' => 'Veuillez télécharger une image valide',
        ])
    ],
])

            ->add('cover_picture', FileType::class, [
                'label' => 'Image de couverture (fichier image)',
                'mapped' => false, // Le champ n'est pas directement lié à la propriété de l'entité
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                    ])
                ],
            ])
            // Ajoutez d'autres champs comme facebook_link, twitter_link, etc. si nécessaire
            ->add('chocolateShop')
            ->add('facebook_link')
            ->add('twitter_link')
            ->add('instagram_link')
            ->add('linkedin_link')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // Vous pouvez aussi définir le token_id si vous avez plusieurs formulaires sur une seule page
            'csrf_token_id'   => 'profile_item',
        ]);
    }
}
