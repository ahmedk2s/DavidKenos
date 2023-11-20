<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
