<?php

namespace App\Form;

use App\Entity\Cadeau;
use App\Entity\ListeCadeau;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Dom\Text;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CadeauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('recommandations', TextareaType::class, [
                                'row_attr' => ['class' => 'text-editor'],
                                'label' => 'Recommandations:',
                                'constraints' => [new Length([
                                    'max' => 500,
                                    "maxMessage"=>"Le texte ne peut pas excéder {{ limit }} caractères."
                                ])],
                            ])
            ->add('url_recomendee', UrlType::class, ['label' => 'Adresse du site ou vous avez vu un modèle:'])
            ->add('taille')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cadeau::class,
        ]);
    }
}
