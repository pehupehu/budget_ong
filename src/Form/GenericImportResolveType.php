<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenericImportResolveType extends AbstractType
{
    const RESOLVE_ADD = 'add';
    const RESOLVE_OVERWRITE = 'overwrite';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('resolve', ChoiceType::class, [
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'choices' => [
                    'generic.form.resolve_add' => self::RESOLVE_ADD,
                    'generic.form.resolve_overwrite' => self::RESOLVE_OVERWRITE,
                ],
                'label' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ],
            ]);
    }
}
