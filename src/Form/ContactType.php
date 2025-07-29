<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => 'Votre nom complet',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire'),
                    new Length(max: 100, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères')
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'placeholder' => 'votre@email.com',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(message: 'L\'email est obligatoire')
                ]
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone (optionnel)',
                'required' => false,
                'attr' => [
                    'placeholder' => '01 12 34 56 78',
                    'pattern' => '^01[0-9]{8}$',
                    'maxlength' => '10',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Regex(
                        pattern: '/^01[0-9]{8}$/',
                        message: 'Le numéro de téléphone doit être un numéro béninois valide (10 chiffres, commençant par 01)'
                    )
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => [
                    'placeholder' => 'Décrivez votre demande ou vos questions...',
                    'rows' => 5,
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(message: 'Le message est obligatoire'),
                    new Length(min: 10, minMessage: 'Le message doit contenir au moins {{ limit }} caractères')
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
