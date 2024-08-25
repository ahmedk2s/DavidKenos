<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Model\SearchData;
use Symfony\Component\Form\Extension\Core\Type\SearchType as BaseSearchType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'attr' => [
                    'placeholder' => 'Rechercher par titre'
                ]
            ]);
        $builder
        ->add('q', BaseSearchType::class, [
            'label' => false,
            'attr' => ['placeholder' => 'Rechercher ...'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class, // Assurez-vous que cette ligne est correctement définie
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
