<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer l'administrateur principal
        $admin = $this->createAdmin();
        $manager->persist($admin);

        // Créer des coachs de test
        $coaches = $this->createCoaches();
        foreach ($coaches as $coach) {
            $manager->persist($coach);
        }

        // Créer des clients de test
        $clients = $this->createClients();
        foreach ($clients as $client) {
            $manager->persist($client);
        }

        // Sauvegarder en base
        $manager->flush();

        echo "✅ Fixtures chargées avec succès !\n";
        echo "👤 Admin: admin@souplessefitness.com / password123\n";
        echo "🏃 Coachs: coach1@souplessefitness.com / password123\n";
        echo "👥 Clients: client1@souplessefitness.com / password123\n";
    }

    private function createAdmin(): User
    {
        $admin = new User();
        $admin->setFirstName('Super')
            ->setLastName('Admin')
            ->setEmail('admin@souplessefitness.com')
            ->setPhone('+33123456789')
            ->setBirthDate(new \DateTime('1985-06-15'))
            ->setGender('Homme')
            ->setAddress('123 Avenue de l\'Administration')
            ->setCity('Paris')
            ->setEmergencyContact('Marie Admin')
            ->setEmergencyPhone('+33987654321')
            ->setRoles(['ROLE_ADMIN'])
            ->setVerified(true)
            ->setActive(true)
            ->setCreatedAt(new \DateTimeImmutable('-1 year'));

        // Hash du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'password123');
        $admin->setPassword($hashedPassword);

        return $admin;
    }

    private function createCoaches(): array
    {
        $coaches = [];
        $coachData = [
            [
                'firstName' => 'Marie',
                'lastName' => 'Dubois',
                'email' => 'coach1@souplessefitness.com',
                'phone' => '+33123456701',
                'birthDate' => '1990-03-20',
                'gender' => 'Femme',
                'address' => '45 Rue des Sportifs',
                'city' => 'Lyon',
                'emergencyContact' => 'Pierre Dubois',
                'emergencyPhone' => '+33987654301',
                'fitnessGoals' => 'Coaching professionnel',
                'experienceLevel' => 'Expert',
                'createdDays' => 180
            ],
            [
                'firstName' => 'Thomas',
                'lastName' => 'Martin',
                'email' => 'coach2@souplessefitness.com',
                'phone' => '+33123456702',
                'birthDate' => '1988-11-12',
                'gender' => 'Homme',
                'address' => '67 Boulevard du Fitness',
                'city' => 'Marseille',
                'emergencyContact' => 'Sophie Martin',
                'emergencyPhone' => '+33987654302',
                'fitnessGoals' => 'Formation et coaching',
                'experienceLevel' => 'Expert',
                'createdDays' => 120
            ],
            [
                'firstName' => 'Julie',
                'lastName' => 'Legrand',
                'email' => 'coach3@souplessefitness.com',
                'phone' => '+33123456703',
                'birthDate' => '1992-07-08',
                'gender' => 'Femme',
                'address' => '12 Allée de la Forme',
                'city' => 'Toulouse',
                'emergencyContact' => 'Marc Legrand',
                'emergencyPhone' => '+33987654303',
                'fitnessGoals' => 'Spécialiste yoga et pilates',
                'experienceLevel' => 'Expert',
                'createdDays' => 90
            ]
        ];

        foreach ($coachData as $data) {
            $coach = new User();
            $coach->setFirstName($data['firstName'])
                ->setLastName($data['lastName'])
                ->setEmail($data['email'])
                ->setPhone($data['phone'])
                ->setBirthDate(new \DateTime($data['birthDate']))
                ->setGender($data['gender'])
                ->setAddress($data['address'])
                ->setCity($data['city'])
                ->setEmergencyContact($data['emergencyContact'])
                ->setEmergencyPhone($data['emergencyPhone'])
                ->setFitnessGoals($data['fitnessGoals'])
                ->setExperienceLevel($data['experienceLevel'])
                ->setRoles(['ROLE_COACH'])
                ->setVerified(true)
                ->setActive(true)
                ->setCreatedAt(new \DateTimeImmutable('-' . $data['createdDays'] . ' days'));

            $hashedPassword = $this->passwordHasher->hashPassword($coach, 'password123');
            $coach->setPassword($hashedPassword);

            $coaches[] = $coach;
        }

        return $coaches;
    }

    private function createClients(): array
    {
        $clients = [];
        $clientData = [
            [
                'firstName' => 'Sophie',
                'lastName' => 'Bernard',
                'email' => 'client1@souplessefitness.com',
                'phone' => '+33123456801',
                'birthDate' => '1995-01-15',
                'gender' => 'Femme',
                'address' => '23 Rue de la Santé',
                'city' => 'Nice',
                'emergencyContact' => 'Paul Bernard',
                'emergencyPhone' => '+33987654801',
                'fitnessGoals' => 'Perte de poids',
                'experienceLevel' => 'Débutant',
                'medicalConditions' => 'Aucune condition particulière',
                'subscribeNewsletter' => true,
                'createdDays' => 30
            ],
            [
                'firstName' => 'Alexandre',
                'lastName' => 'Durand',
                'email' => 'client2@souplessefitness.com',
                'phone' => '+33123456802',
                'birthDate' => '1987-09-22',
                'gender' => 'Homme',
                'address' => '56 Avenue du Bien-être',
                'city' => 'Bordeaux',
                'emergencyContact' => 'Claire Durand',
                'emergencyPhone' => '+33987654802',
                'fitnessGoals' => 'Gain de muscle',
                'experienceLevel' => 'Intermédiaire',
                'medicalConditions' => 'Allergie aux acariens',
                'subscribeNewsletter' => false,
                'createdDays' => 15
            ],
            [
                'firstName' => 'Émilie',
                'lastName' => 'Rousseau',
                'email' => 'client3@souplessefitness.com',
                'phone' => '+33123456803',
                'birthDate' => '1993-04-30',
                'gender' => 'Femme',
                'address' => '78 Place de la Forme',
                'city' => 'Strasbourg',
                'emergencyContact' => 'Vincent Rousseau',
                'emergencyPhone' => '+33987654803',
                'fitnessGoals' => 'Remise en forme',
                'experienceLevel' => 'Débutant',
                'medicalConditions' => 'Problème de dos léger',
                'subscribeNewsletter' => true,
                'createdDays' => 7
            ],
            [
                'firstName' => 'Lucas',
                'lastName' => 'Moreau',
                'email' => 'client4@souplessefitness.com',
                'phone' => '+33123456804',
                'birthDate' => '1991-12-03',
                'gender' => 'Homme',
                'address' => '34 Rue de l\'Énergie',
                'city' => 'Lille',
                'emergencyContact' => 'Anaïs Moreau',
                'emergencyPhone' => '+33987654804',
                'fitnessGoals' => 'Endurance',
                'experienceLevel' => 'Avancé',
                'medicalConditions' => null,
                'subscribeNewsletter' => true,
                'createdDays' => 45
            ],
            [
                'firstName' => 'Camille',
                'lastName' => 'Petit',
                'email' => 'client5@souplessefitness.com',
                'phone' => '+33123456805',
                'birthDate' => '1996-08-18',
                'gender' => 'Femme',
                'address' => '91 Boulevard de la Vitalité',
                'city' => 'Nantes',
                'emergencyContact' => 'Maxime Petit',
                'emergencyPhone' => '+33987654805',
                'fitnessGoals' => 'Tonification',
                'experienceLevel' => 'Intermédiaire',
                'medicalConditions' => 'Asthme léger',
                'subscribeNewsletter' => false,
                'createdDays' => 60
            ],
            [
                'firstName' => 'Julien',
                'lastName' => 'Roux',
                'email' => 'client6@souplessefitness.com',
                'phone' => '+33123456806',
                'birthDate' => '1989-02-14',
                'gender' => 'Homme',
                'address' => '15 Impasse du Sport',
                'city' => 'Montpellier',
                'emergencyContact' => 'Laura Roux',
                'emergencyPhone' => '+33987654806',
                'fitnessGoals' => 'Préparation physique',
                'experienceLevel' => 'Avancé',
                'medicalConditions' => 'Ancienne blessure au genou',
                'subscribeNewsletter' => true,
                'createdDays' => 75
            ]
        ];

        foreach ($clientData as $data) {
            $client = new User();
            $client->setFirstName($data['firstName'])
                ->setLastName($data['lastName'])
                ->setEmail($data['email'])
                ->setPhone($data['phone'])
                ->setBirthDate(new \DateTime($data['birthDate']))
                ->setGender($data['gender'])
                ->setAddress($data['address'])
                ->setCity($data['city'])
                ->setEmergencyContact($data['emergencyContact'])
                ->setEmergencyPhone($data['emergencyPhone'])
                ->setFitnessGoals($data['fitnessGoals'])
                ->setExperienceLevel($data['experienceLevel'])
                ->setMedicalConditions($data['medicalConditions'])
                ->setSubscribeNewsletter($data['subscribeNewsletter'])
                ->setRoles(['ROLE_CLIENT'])
                ->setVerified(true)
                ->setActive(true)
                ->setCreatedAt(new \DateTimeImmutable('-' . $data['createdDays'] . ' days'));

            $hashedPassword = $this->passwordHasher->hashPassword($client, 'password123');
            $client->setPassword($hashedPassword);

            $clients[] = $client;
        }

        return $clients;
    }
}
