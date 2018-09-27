<?php

namespace App\Form;

use App\Entity\Delegation;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DelegationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Delegation::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, [
            'required' => true,
        ]);
        $builder->add('code', null, [
            'required' => true,
        ])
            ->add('back', ButtonType::class)
            ->add('save', SubmitType::class)
        ;
    }
}