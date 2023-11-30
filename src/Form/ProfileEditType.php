<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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
            ])
            ->add('facebook_link')
            ->add('twitter_link')
            ->add('instagram_link')
            ->add('linkedin_link')
            ->add('chocolateShop');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'profile_item',
        ]);
    }
}