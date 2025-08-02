<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Informations personnelles obligatoires
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Votre prénom'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est obligatoire']),
                    new Length(['min' => 2, 'minMessage' => 'Le prénom doit contenir au moins 2 caractères'])
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Votre nom'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire']),
                    new Length(['min' => 2, 'minMessage' => 'Le nom doit contenir au moins 2 caractères'])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'votre@email.com'
                ]
            ])

            // NOUVEAU : Sélection du type d'utilisateur
            ->add('userType', ChoiceType::class, [
                'label' => 'Type d\'utilisateur',
                'mapped' => false, // Ce champ n'est pas mappé directement à l'entité
                'choices' => [
                    'Sélectionner votre profil' => '',
                    'Client (Je souhaite m\'entraîner)' => 'ROLE_CLIENT',
                    'Coach (Je souhaite enseigner)' => 'ROLE_COACH',
                ],
                'attr' => [
                    'class' => 'form-select form-select-lg custom-select'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner votre type d\'utilisateur'])
                ],
                'help' => 'Choisissez "Client" si vous souhaitez suivre des cours, "Coach" si vous souhaitez en dispenser.'
            ])

            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => '01 12 34 56 78',
                    'pattern' => '^01[0-9]{8}$',
                    'maxlength' => '10'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le téléphone est obligatoire']),
                    new Regex([
                        'pattern' => '/^01[0-9]{8}$/',
                        'message' => 'Le numéro de téléphone doit être un numéro béninois valide (10 chiffres, commençant par 01)'
                    ])
                ]
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'max' => (new \DateTime('-18 years'))->format('Y-m-d') // Minimum 18 ans
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La date de naissance est obligatoire']),
                    new Callback([$this, 'validateAge'])
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Sélectionner votre genre' => '',
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre'
                ],
                'attr' => [
                    'class' => 'form-select form-select-lg custom-select'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le genre est obligatoire'])
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Numéro et nom de rue'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'L\'adresse est obligatoire'])
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Cotonou'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La ville est obligatoire'])
                ]
            ])

            // Informations d'urgence (optionnelles)
            ->add('emergencyContact', TextType::class, [
                'label' => 'Contact d\'urgence',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Nom de la personne à contacter'
                ]
            ])
            ->add('emergencyPhone', TelType::class, [
                'label' => 'Téléphone d\'urgence',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => '01 12 34 56 78',
                    'pattern' => '^01[0-9]{8}$',
                    'maxlength' => '10'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^01[0-9]{8}$/',
                        'message' => 'Le numéro de téléphone d\'urgence doit être un numéro béninois valide (10 chiffres, commençant par 01)'
                    ])
                ]
            ])

            // Informations fitness (optionnelles)
            ->add('fitnessGoals', ChoiceType::class, [
                'label' => 'Objectifs fitness',
                'required' => false,
                'choices' => [
                    'Choisir votre objectif principal' => '',
                    'Perte de poids' => 'perte_poids',
                    'Prise de muscle' => 'prise_muscle',
                    'Remise en forme générale' => 'remise_forme',
                    'Améliorer l\'endurance' => 'endurance',
                    'Renforcement musculaire' => 'force',
                    'Bien-être et détente' => 'bien_etre',
                    'Préparation sportive' => 'preparation_sportive'
                ],
                'attr' => [
                    'class' => 'form-select form-select-lg custom-select'
                ]
            ])
            ->add('experienceLevel', ChoiceType::class, [
                'label' => 'Niveau d\'expérience',
                'required' => false,
                'choices' => [
                    'Évaluer votre niveau actuel' => '',
                    'Débutant (jamais fait de sport)' => 'debutant',
                    'Intermédiaire (quelques mois d\'expérience)' => 'intermediaire',
                    'Avancé (plusieurs années d\'expérience)' => 'avance',
                    'Expert (athlète confirmé)' => 'expert'
                ],
                'attr' => [
                    'class' => 'form-select form-select-lg custom-select'
                ]
            ])
            ->add('medicalConditions', TextareaType::class, [
                'label' => 'Conditions médicales',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Veuillez indiquer toute condition médicale importante pour votre sécurité et celle de nos coachs :

• Blessures récentes ou chroniques (dos, genoux, épaules, etc.)
• Problèmes cardiovasculaires ou respiratoires
• Diabète, hypertension ou autres maladies chroniques
• Allergies médicamenteuses ou alimentaires
• Chirurgies récentes ou prothèses
• Traitement médicamenteux en cours
• Grossesse ou allaitement
• Toute autre condition nécessitant une attention particulière

Si aucune condition particulière, vous pouvez laisser ce champ vide.'
                ]
            ])

            // Options
            ->add('subscribeNewsletter', CheckboxType::class, [
                'label' => 'Je souhaite recevoir la newsletter',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions d\'utilisation',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\'utilisation.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Minimum 6 caractères'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmer le mot de passe',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Confirmer le mot de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez confirmer votre mot de passe'
                    ])
                ]
            ])
        ;
    }

    public function validateAge($birthDate, ExecutionContextInterface $context)
    {
        if (!$birthDate instanceof \DateTimeInterface) {
            return;
        }

        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        if ($age < 18) {
            $context->buildViolation('Vous devez être âgé d\'au moins 18 ans pour vous inscrire.')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
