<?php

namespace App\Form;

use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Site::class,
                'required' => false
            ])
            ->add('name', TextType::class, [
                'required' => false
            ])
            ->add('entre', DateType::class, [
                'required' => false
            ])
            ->add('et', DateType::class, [
                'required' => false
            ])
            ->add('sortie_organisateur', CheckboxType::class, [
                'required' => false
            ])
            ->add('sortie_inscrite', CheckboxType::class, [
                'required' => false
            ])
            ->add('sortie_non_inscrite', CheckboxType::class, [
                'required' => false
            ])
            ->add('sortie_passees', CheckboxType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
