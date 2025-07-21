<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // 👤 Informations personnelles
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'mapped' => false,
                'constraints' => [new NotBlank(['message' => 'Nom requis'])]
            ])

            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'mapped' => false,
                'constraints' => [new NotBlank(['message' => 'Prénom requis'])]
            ])

            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'mapped' => false,
                'required' => false
            ])

            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'mapped' => false,
                'widget' => 'single_text',
                'required' => false
            ])

            ->add('adresse', TextareaType::class, [
                'label' => 'Adresse',
                'mapped' => false,
                'required' => false,
                'attr' => ['rows' => 3]
            ])

            // 🔐 Informations de connexion
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un email']),
                    new Email(['message' => 'Email invalide'])
                ]
            ])

            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un mot de passe']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('profession', TextType::class, [
                'label' => 'Profession',
                'mapped' => false,
                'required' => false
            ])

            ->add('contactUrgence', TextType::class, [
                'label' => 'Contact d\'urgence',
                'mapped' => false,
                'required' => false
            ])

            // 🎭 Rôle de l'utilisateur
            ->add('typeUtilisateur', ChoiceType::class, [
                'label' => 'Je m\'inscris en tant que :',
                'mapped' => false,
                'choices' => [
                    'Client' => 'ROLE_CLIENT',
                    'Coach' => 'ROLE_COACH',
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 'ROLE_CLIENT',
                'constraints' => [new NotBlank(['message' => 'Choisissez votre profil'])]
            ])

            // ✅ Conditions d'utilisation
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions d\'utilisation',
                'mapped' => false,
                'constraints' => [
                    new IsTrue(['message' => 'Vous devez accepter nos conditions.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
