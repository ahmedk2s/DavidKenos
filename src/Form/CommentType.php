<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('post', EntityType::class, [
                'class' => Post::class,
                'choices' => $options['current_post'] ? [$options['current_post']] : $options['posts'],
                'choice_label' => function(Post $post) {
                    return $post->getTitle();
                },
                'disabled' => (bool) $options['current_post'], // Désactive le champ si 'current_post' est fourni
                'multiple' => false,
                'required' => true, // Rend le champ obligatoire
            ])
            ->add('text')
            ->add('user', EntityType::class, [
                'class' => User::class,
                // Utilise l'utilisateur actuel comme seul choix et désactive le champ
                'choices' => $options['current_user'] ? [$options['current_user']] : [],
                'choice_label' => function(User $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
                'disabled' => true, // Toujours désactivé car l'utilisateur ne doit pas être modifié
                'multiple' => false,
                'required' => true, // Rend le champ obligatoire
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'posts' => [], 
            'current_post' => null,
            'current_user' => null,
        ]);
    }
}
