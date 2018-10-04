<?php

namespace App\Form\BatchActions;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GenericBatchActionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [];
        foreach ($options['data']['choices'] as $choice) {
            $choices['generic.list.' . $choice] = $choice;
        }
        $builder
            ->add('action', ChoiceType::class, [
                'required' => false,
                'choices' => $choices,
                'placeholder' => 'generic.list.choose'
            ])
            ->add('ids', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'label' => false,
                    'multiple' => true,
                    'expanded' => true
                ],
            ])
            ->add('submit', SubmitType::class);
    }
}