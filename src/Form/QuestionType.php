<?php

namespace App\Form;

use App\Entity\Jeux;
use App\Entity\Question;
use App\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule')
            ->add('jeu', EntityType::class, [
                'class' => Jeux::class,
                'choice_label' => 'nom',
            ])
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'nom'
            ])
            ->add('difficulte', ChoiceType::class, [
                'choices' => [
                    'Choisir une difficulté' => 0,
                    'Facile' => 1,
                    'Difficile' => 2,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
