<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class GenericImportStep1Type
 * @package App\Form
 */
class GenericImportStep1Type extends AbstractType
{
    /** @var array */
    private $file_headers_required = [];
    
    /** @var TranslatorInterface */
    private $translator;

    /**
     * GenericImportStep1Type constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined([
            'file_headers_required'
        ]);
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->file_headers_required = $options['file_headers_required'] ?? [];
        sort($this->file_headers_required);

        $builder
            ->add('file', FileType::class, [
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'text/plain',
                            'text/csv',
                        ]
                    ]),
                    new Callback([$this, 'validateFileHeaders'])
                ]
            ])
            ->add('back', ButtonType::class)
            ->add('import', SubmitType::class);
    }

    /**
     * @param UploadedFile $file
     * @param ExecutionContextInterface $context
     * @return bool
     */
    public function validateFileHeaders(UploadedFile $file, ExecutionContextInterface $context)
    {
        if (!count($this->file_headers_required)) {
            return true;
        }

        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $data = $serializer->decode(file_get_contents($file->getPathname()), 'csv');

        $file_headers = array_keys($data[0]);
        sort($file_headers);

        if (count(array_diff($this->file_headers_required, $file_headers))) {
            $context->addViolation($this->translator->trans('generic.message.violation.file_headers', ['file_headers_required' => implode(', ', $this->file_headers_required)]));
        }
    }
}