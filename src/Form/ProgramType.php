<?php

namespace App\Form;

use App\Entity\Program;
use App\Entity\Actor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'style' => 'max-width:28rem; margin:auto;'
                ],
            ])
            ->add('summary', TextareaType::class, [
                'attr' => [
                    'style' => 'max-width:28rem; margin:auto;'
                ],
            ])
            ->add('poster', TextType::class, [
                'attr' => [
                    'style' => 'max-width:28rem; margin:auto;'
                ],
            ])
            ->add('category', null, [
                'choice_label' => 'name',
                'attr' => [
                    'style' => 'max-width:28rem; margin:auto;'
                ],
                ])
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
