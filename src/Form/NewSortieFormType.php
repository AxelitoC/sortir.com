<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use function Sodium\add;


class NewSortieFormType extends AbstractType
{
    protected $security;

    public function __construct(Security $security){
        return $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('infoSortie')
            ->add('site', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'disabled'=>true,
                'mapped'=>false,
                'data'=> $this->security->getUser()->getSite(),
            ])
            ->add('ville', EntityType::class, [
                'mapped'=>false,
                'class'=>Ville::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('lieu', CollectionType::class,[
                 'mapped'=>false,
                ])
            ->add('rue', TextType::class, [
                'disabled' => true,
                'mapped' => false,
            ])
            ->add('codepostale', TextType::class, [
                'disabled' => true,
                'mapped' => false,
            ])
            ->add('online', SubmitType::class,[
               'label'=> 'Publier une sortie'
            ]);

        $builder->get('ville')->addEventListener(
          FormEvents::POST_SUBMIT,
          function (FormEvent $event){
              $form = $event->getForm();
              $this->addLieuField($form->getParent(), $form->getData());
          }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event){
                $data = $event->getData();
                /* @var $lieu \App\Entity\Lieu */
                $lieu = $data->getLieu();
                $form = $event->getForm();
                if($lieu){
                    $ville = $lieu->getVille();
                    $this->addLieuField($form, $ville);
                    $form->get('ville')->setData($ville);
                }else{
                    $this->addLieuField($form, null);
                }

            }
        );
    }

    public function addLieuField(FormInterface $form, ?Ville $ville){

        $builder = $form->add('lieu', EntityType::class,
                [
                    'class'           => 'App\Entity\Lieu',
                    'placeholder'     => $ville ? 'Selectionnez votre lieu' : 'Selectionnez votre ville',
                    'required'        => true,
                    'auto_initialize' => false,
                    'choices'         => $ville ? $ville->getLieus() :  []
                ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
