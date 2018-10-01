<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class GenericImportType
 * @package App\Form
 */
class GenericImportType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'text/plain',
                            'text/csv',
                        ]
                    ])
                ]
            ])
            ->add('back', ButtonType::class)
            ->add('import', SubmitType::class);
    }
}