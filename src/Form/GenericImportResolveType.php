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
    const RESOLVE_SKIP = 'skip';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $object = $event->getData();
                $form = $event->getForm();

                $choices = [];
                if ($object->getResolve() === self::RESOLVE_ADD) {
                    $choices['generic.choice.resolve_add'] = self::RESOLVE_ADD;
                } elseif ($object->getResolve() === self::RESOLVE_OVERWRITE) {
                    $choices['generic.choice.resolve_overwrite'] = self::RESOLVE_OVERWRITE;
                }
                $choices['generic.choice.resolve_skip'] = self::RESOLVE_SKIP;

                $form
                    ->add('resolve', ChoiceType::class, [
                        'expanded' => false,
                        'multiple' => false,
                        'required' => true,
                        'choices' => $choices,
                        'label' => false,
                        'attr' => [
                            'class' => 'form-control-sm'
                        ],
                    ]);
            });
    }
}