<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\ChocolateShop;

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
            ->add('chocolate_shop', EntityType::class, [
                'class' => ChocolateShop::class,
                'choice_label' => 'city',
                'label' => 'Chocolaterie',
            ])
             ->add('date_creation', DateType::class, [
                'widget' => 'single_text', 
                'label' => 'Date de création',
                'required' => false, 
             ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
