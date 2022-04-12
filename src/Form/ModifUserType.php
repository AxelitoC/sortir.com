<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password',RepeatedType::class, [
                'required' => false,
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label'=>'Repeat Password'],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('pseudo')
            ->add('telephone')
            //todo Penser à faire ville de rattachement quand tables Ville+Lieu créées
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
