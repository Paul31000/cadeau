<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class, [
                                'row_attr' => ['class' => 'text-editor'],
                                'required'   => true,
                                'label' => 'message:*',
                                'sanitize_html' => true,
                                'constraints' => [new Length([
                                    'max' => 5000,
                                    "maxMessage"=>"Le texte ne peut pas excéder {{ limit }} caractères."
                                ])],
                            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
