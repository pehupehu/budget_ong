<?php

namespace App\Form;

use App\Entity\User;
use App\Twig\AppExtension;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'required' => true,
            ])
            ->add('lastname', null, [
                'required' => true,
            ]);

        if (!$options['data']->getId()) {
            $builder->add('username', EmailType::class);

            $builder->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'admin_user.field.password'],
                'second_options' => ['label' => 'admin_user.field.repeatedpassword'],
            ]);
        }

        $builder
            ->add('role', ChoiceType::class, [
                'required' => true,
                'choices' => User::getRolesChoices(),
            ])
            ->add('locale', ChoiceType::class, [
                'required' => true,
                'choices' => AppExtension::getSupportedLocales(),
            ])
            ->add('back', ButtonType::class)
            ->add('save', SubmitType::class);
    }
}