<?php

namespace App\Form;

use App\Entity\News;
use App\Entity\ChocolateShop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Entrez le titre de l\'actualité'],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Texte',
                'attr' => ['placeholder' => 'Entrez le texte de l\'actualité'],
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de l\'actualité',
            ])
            ->add('date_creation', DateType::class, [
                'widget' => 'single_text', 
                'label' => 'Date de création',
                'required' => false, 
            ]);

        // Ajoutez le champ chocolate_shop seulement si l'utilisateur n'est pas un super admin
        if ($options['chocolate_shop_editable']) {
            $builder->add('chocolate_shop', EntityType::class, [
                'class' => ChocolateShop::class,
                'choice_label' => 'city', 
                'label' => 'Chocolaterie',
                'placeholder' => 'Sélectionnez une chocolaterie',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
            'chocolate_shop_editable' => false, 
            'is_super_admin' => false, 
        ]);
    }
}
