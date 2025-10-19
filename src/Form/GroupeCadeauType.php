<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\GroupeCadeau;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GroupeCadeauType extends AbstractType
{
    public function __construct(private UserRepository $utilRep)
    {
        

    }

    public function buildForm(FormBuilderInterface $builder, array $options ): void
    {
       
        $builder
            ->add('nomGroupe')
            /* ->add('typeGroupe', ChoiceType::class, [
                'required'   => true,
                'choices' => [
                    ""=>null,
                    "SecretSanta" => "SecretSanta",
                    "GroupeNoel" => "GroupeNoel",
                    "Anniversaire" => "Anniversaire"
                ],
                'label' => 'Au sein de:*',
                'placeholder' => "Sélectionner un type"
            ]) */

            ->add('utilisateurConcernes', EntityType::class, [
                'required' => true,
                'choices'=>$options['users'],
                'multiple'=>true,
                'label' => 'Utilisateurs du groupe:*',
                'class' => User::class,
                'placeholder' => ' ',
                'autocomplete' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupeCadeau::class,
            'users'=>Collection::class
        ]);
    }
}
