<?php

namespace App\Form\Filters;

use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserFiltersType
 * @package App\Form\Filters
 */
class UserFiltersType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('get')
            ->add('firstname', TextType::class, [
                'required' => false,
            ])
            ->add('lastname', null, [
                'required' => false,
            ])
            ->add('username', null, [
                'required' => false,
            ])
            ->add('role', ChoiceType::class, [
                'required' => false,
                'choices' => UserRepository::getRoles()
            ])
            ->add('reset', ButtonType::class)
            ->add('submit', SubmitType::class);
    }
}