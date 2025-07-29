<?php

namespace App\DataFixtures;

use App\Entity\Abonnement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AbonnementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $abonnements = [
            [
                'nom' => 'Abonnement Découverte',
                'type' => 'mensuel',
                'prix' => 29,
                'dureeJours' => 30,
                'description' => 'Parfait pour débuter ! Accès illimité à la salle de musculation et aux cours collectifs de base.',
                'actif' => true,
                'avantages' => [
                    'Accès libre 7j/7 de 6h à 22h',
                    'Cours collectifs inclus',
                    'Vestiaires et douches',
                    'Programme d\'entraînement personnalisé',
                    'Suivi des progrès en ligne'
                ]
            ],
            [
                'nom' => 'Abonnement Forme',
                'type' => 'mensuel',
                'prix' => 49,
                'dureeJours' => 30,
                'description' => 'L\'abonnement le plus populaire ! Accès complet + séances de coaching personnalisé.',
                'actif' => true,
                'avantages' => [
                    'Tous les avantages Découverte',
                    '2 séances de coaching personnalisé/mois',
                    'Accès aux cours premium (Yoga, Pilates)',
                    'Espace détente et sauna',
                    'Nutrition coaching inclus',
                    'Application mobile premium'
                ]
            ],
            [
                'nom' => 'Abonnement Premium',
                'type' => 'mensuel',
                'prix' => 79,
                'dureeJours' => 30,
                'description' => 'L\'excellence du fitness ! Coaching illimité et services VIP pour des résultats optimaux.',
                'actif' => true,
                'avantages' => [
                    'Tous les avantages Forme',
                    'Coaching personnalisé illimité',
                    'Accès prioritaire aux équipements',
                    'Vestiaire VIP privé',
                    'Massages thérapeutiques (2/mois)',
                    'Plan nutrition sur-mesure',
                    'Invitations aux événements exclusifs'
                ]
            ],
            [
                'nom' => 'Abonnement Étudiant',
                'type' => 'mensuel',
                'prix' => 19,
                'dureeJours' => 30,
                'description' => 'Tarif spécial étudiant avec accès complet aux installations de base.',
                'actif' => true,
                'avantages' => [
                    'Accès libre en semaine 8h-17h',
                    'Cours collectifs de base',
                    'Vestiaires et douches',
                    'Support en ligne',
                    'Réductions sur les services'
                ]
            ],
            [
                'nom' => 'Abonnement Annuel',
                'type' => 'annuel',
                'prix' => 499,
                'dureeJours' => 365,
                'description' => 'Économisez avec notre formule annuelle ! Tous les avantages Forme pour 12 mois.',
                'actif' => true,
                'avantages' => [
                    'Équivalent Forme pour 12 mois',
                    '2 mois offerts (10 mois payés)',
                    '4 séances coaching personnalisé/mois',
                    'Accès invité (2 fois/mois)',
                    'Garantie satisfaction 30 jours',
                    'Gel d\'abonnement (1 mois/an)'
                ]
            ],
            [
                'nom' => 'Abonnement Famille',
                'type' => 'mensuel',
                'prix' => 89,
                'dureeJours' => 30,
                'description' => 'Parfait pour toute la famille ! Jusqu\'à 4 membres avec accès complet.',
                'actif' => true,
                'avantages' => [
                    'Accès pour 4 personnes maximum',
                    'Tous les cours collectifs inclus',
                    'Cours enfants/ados spécialisés',
                    'Vestiaires familiaux',
                    'Activités famille mensuelles',
                    'Réductions boutique et café'
                ]
            ]
        ];

        foreach ($abonnements as $index => $abonnementData) {
            $abonnement = new Abonnement();
            $abonnement->setNom($abonnementData['nom'])
                ->setType($abonnementData['type'])
                ->setPrix($abonnementData['prix'])
                ->setDureeJours($abonnementData['dureeJours'])
                ->setDescription($abonnementData['description'])
                ->setActif($abonnementData['actif'])
                ->setDateCreation(new \DateTime());

            $manager->persist($abonnement);

            // Référence pour les autres fixtures
            $this->addReference('abonnement_' . $index, $abonnement);
        }

        $manager->flush();

        echo "✅ Abonnements créés avec succès !\n";
    }
}
