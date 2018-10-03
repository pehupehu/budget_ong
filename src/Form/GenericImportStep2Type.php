<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GenericImportStep2Type
 * @package App\Form
 */
class GenericImportStep2Type extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objects', CollectionType::class, [
                'entry_type' => GenericImportResolveType::class,
                'entry_options' => [
                    'label' => false,
                ],
            ])
            ->add('back', ButtonType::class)
            ->add('save', SubmitType::class);
    }
}