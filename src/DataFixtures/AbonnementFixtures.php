<?php

namespace App\DataFixtures;

use App\Entity\Abonnement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AbonnementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Vider d'abord tous les abonnements existants
        $repository = $manager->getRepository(Abonnement::class);
        $existingAbonnements = $repository->findAll();
        foreach ($existingAbonnements as $abonnement) {
            $manager->remove($abonnement);
        }
        $manager->flush();

        $abonnements = [
            [
                'nom' => 'Séance Unique',
                'type' => 'seance',
                'prix' => 1500,
                'dureeJours' => 1,
                'description' => 'Accès à la salle pour une séance de 2h maximum. Parfait pour découvrir nos installations.',
                'actif' => true,
                'avantages' => [
                    'Accès salle de musculation (2h)',
                    'Utilisation des équipements cardio',
                    'Vestiaires et douches',
                    'Accueil et orientation'
                ]
            ],
            [
                'nom' => 'Carnet 10 Séances',
                'type' => 'carnet',
                'prix' => 10000,
                'dureeJours' => 30,
                'description' => 'Carnet de 10 séances à utiliser dans les 30 jours. Idéal pour un usage occasionnel.',
                'actif' => true,
                'avantages' => [
                    '10 séances de 2h chacune',
                    'Validité : 30 jours',
                    'Accès complet aux équipements',
                    'Vestiaires et douches',
                    'Flexibilité d\'horaires'
                ]
            ],
            [
                'nom' => 'Carnet 15 Séances',
                'type' => 'carnet',
                'prix' => 20000,
                'dureeJours' => 90,
                'description' => 'Carnet de 15 séances valable 3 mois. Excellent rapport qualité-prix pour une pratique régulière.',
                'actif' => true,
                'avantages' => [
                    '15 séances de 2h chacune',
                    'Validité : 90 jours',
                    'Accès complet aux équipements',
                    'Vestiaires et douches',
                    'Programme d\'entraînement offert'
                ]
            ],
            [
                'nom' => 'Abonnement 1 Mois',
                'type' => 'mensuel',
                'prix' => 10000, // Prix promotionnel (au lieu de 15000)
                'prixOriginal' => 15000,
                'dureeJours' => 30,
                'description' => '🔥 PROMOTION ! Accès illimité pendant 1 mois. De 15.000 à 10.000 FCFA !',
                'actif' => true,
                'promotion' => true,
                'avantages' => [
                    'Accès illimité 7j/7',
                    'Horaires : Lun-Ven 7h-22h, Sam 7h-20h, Dim 7h-14h',
                    'Tous équipements disponibles',
                    'Cours collectifs inclus',
                    'Vestiaires et douches',
                    'Suivi personnalisé'
                ]
            ],
            [
                'nom' => 'Abonnement 1 Mois + Suivi Personnel',
                'type' => 'mensuel_plus',
                'prix' => 15000, // Prix promotionnel (au lieu de 20000)
                'prixOriginal' => 20000,
                'dureeJours' => 30,
                'description' => '🔥 PROMOTION ! Abonnement 1 mois avec coaching personnalisé. De 20.000 à 15.000 FCFA !',
                'actif' => true,
                'promotion' => true,
                'avantages' => [
                    'Tous les avantages de l\'abonnement 1 mois',
                    'Coaching personnalisé inclus',
                    'Plan d\'entraînement sur-mesure',
                    'Suivi nutritionnel de base',
                    'Séances individuelles programmées',
                    'Conseils d\'experts'
                ]
            ],
            [
                'nom' => 'Abonnement 1 Mois Couple',
                'type' => 'mensuel_couple',
                'prix' => 25000,
                'dureeJours' => 30,
                'description' => 'Abonnement mensuel pour 2 personnes. Entraînez-vous ensemble et économisez !',
                'actif' => true,
                'avantages' => [
                    'Accès pour 2 personnes',
                    'Tous les avantages individuels',
                    'Séances duo possibles',
                    'Programmes d\'entraînement couple',
                    'Motivation partagée'
                ]
            ],
            [
                'nom' => 'Abonnement 1 Mois + Suivi Couple',
                'type' => 'mensuel_plus_couple',
                'prix' => 40000,
                'dureeJours' => 30,
                'description' => 'Abonnement mensuel avec suivi personnel pour 2 personnes. L\'excellence à deux !',
                'actif' => true,
                'avantages' => [
                    'Accès pour 2 personnes',
                    'Coaching personnalisé pour chacun',
                    'Plans d\'entraînement individualisés',
                    'Suivi nutritionnel couple',
                    'Séances duo et individuelles'
                ]
            ],
            [
                'nom' => 'Abonnement 3 Mois',
                'type' => 'trimestriel',
                'prix' => 40000,
                'dureeJours' => 90,
                'description' => 'Engagement trimestriel pour des résultats durables. Excellent investissement fitness.',
                'actif' => true,
                'avantages' => [
                    'Accès illimité 3 mois',
                    'Tous les cours collectifs',
                    'Suivi de progression',
                    'Plan nutritionnel de base',
                    'Séances coaching incluses (2/mois)',
                    'Meilleur rapport qualité-prix'
                ]
            ],
            [
                'nom' => 'Abonnement 3 Mois Couple',
                'type' => 'trimestriel_couple',
                'prix' => 75000,
                'dureeJours' => 90,
                'description' => 'Formule trimestrielle pour couple. 3 mois d\'entraînement à deux avec suivi personnalisé.',
                'actif' => true,
                'avantages' => [
                    'Accès couple 3 mois',
                    'Coaching personnalisé duo',
                    'Plans nutrition individualisés',
                    'Séances couple et individuelles',
                    'Suivi de progression détaillé'
                ]
            ],
            [
                'nom' => 'Abonnement 6 Mois',
                'type' => 'semestriel',
                'prix' => 70000,
                'dureeJours' => 180,
                'description' => 'Engagement semestriel pour une transformation complète. Résultats garantis !',
                'actif' => true,
                'avantages' => [
                    'Accès illimité 6 mois',
                    'Coaching personnalisé intensif',
                    'Plan nutritionnel complet',
                    'Suivi médical sportif',
                    'Cours premium inclus',
                    'Garantie résultats'
                ]
            ],
            [
                'nom' => 'Abonnement 6 Mois Couple',
                'type' => 'semestriel_couple',
                'prix' => 120000,
                'dureeJours' => 180,
                'description' => 'Formule semestrielle couple. 6 mois de transformation à deux avec accompagnement VIP.',
                'actif' => true,
                'avantages' => [
                    'Accès couple 6 mois',
                    'Coaching VIP personnalisé',
                    'Plans nutrition sur-mesure',
                    'Suivi médical couple',
                    'Séances privées incluses',
                    'Accompagnement premium'
                ]
            ],
            [
                'nom' => 'Abonnement Annuel',
                'type' => 'annuel',
                'prix' => 120000,
                'dureeJours' => 365,
                'description' => 'L\'abonnement le plus économique ! Une année complète de fitness avec tous les avantages.',
                'actif' => true,
                'avantages' => [
                    'Accès illimité 1 an',
                    'Coaching personnalisé illimité',
                    'Plan nutrition complet',
                    'Suivi médical régulier',
                    'Tous cours inclus',
                    'Invitations événements exclusifs',
                    'Économie maximale'
                ]
            ],
            [
                'nom' => 'Abonnement Annuel Couple',
                'type' => 'annuel_couple',
                'prix' => 200000,
                'dureeJours' => 365,
                'description' => 'Formule annuelle couple. Une année de fitness à deux avec tous les services premium.',
                'actif' => true,
                'avantages' => [
                    'Accès couple 1 an',
                    'Services premium illimités',
                    'Coaching couple personnalisé',
                    'Plans nutrition individualisés',
                    'Suivi médical complet',
                    'Événements couples exclusifs',
                    'Meilleur tarif couple'
                ]
            ],
            [
                'nom' => 'Fit Dance - 1 Mois',
                'type' => 'activite_speciale',
                'prix' => 10000,
                'dureeJours' => 30,
                'description' => 'Cours de Fit Dance exclusifs. Dansez, transpirez et amusez-vous !',
                'actif' => true,
                'avantages' => [
                    'Cours Fit Dance illimités',
                    'Ambiance festive garantie',
                    'Chorégraphies variées',
                    'Instructeurs certifiés',
                    'Tous niveaux acceptés',
                    'Séances dynamiques et fun'
                ]
            ],
            [
                'nom' => 'Taekwondo - 1 Mois',
                'type' => 'activite_speciale',
                'prix' => 10000,
                'dureeJours' => 30,
                'description' => 'Cours de Taekwondo traditionnel. Discipline, technique et self-défense.',
                'actif' => true,
                'avantages' => [
                    'Cours Taekwondo traditionnels',
                    'Techniques de self-défense',
                    'Discipline et concentration',
                    'Maître certifié',
                    'Progression par ceintures',
                    'Enfants et adultes'
                ]
            ],
            [
                'nom' => 'Boxe - 1 Mois',
                'type' => 'activite_speciale',
                'prix' => 10000,
                'dureeJours' => 30,
                'description' => 'Cours de boxe intensive. Cardio, technique et dépassement de soi.',
                'actif' => true,
                'avantages' => [
                    'Cours de boxe intensifs',
                    'Entraînement cardio optimal',
                    'Techniques de frappe',
                    'Coach professionnel',
                    'Équipements fournis',
                    'Condition physique excellente'
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

            // Ajouter le prix original pour les promotions
            if (isset($abonnementData['prixOriginal'])) {
                // Nous ajouterons cette propriété à l'entité si nécessaire
            }

            $manager->persist($abonnement);

            // Référence pour les autres fixtures
            $this->addReference('abonnement_' . $index, $abonnement);
        }

        $manager->flush();

        echo "✅ Abonnements Souplesse Fitness créés avec succès !\n";
        echo "📋 Total : " . count($abonnements) . " formules d'abonnement\n";
        echo "💰 Devise : FCFA (Franc CFA)\n";
        echo "🎯 Promotions actives sur les formules mensuelles\n";
        echo "🏃‍♂️ Activités spéciales : Fit Dance, Taekwondo, Boxe\n";
    }
}
