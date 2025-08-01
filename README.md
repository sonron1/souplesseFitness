# Souplesse Fitness 💪

**STABILITÉ - PROGRÈS - RÉUSSITE**

Une application web moderne de gestion de salle de sport développée avec Symfony 7.3, permettant la gestion des abonnements, des cours, du coaching personnalisé et bien plus encore.


## ⚠️ Avertissement - Droits d'auteur

**🚨 ATTENTION : Ce projet est protégé par des droits d'auteur**

Ce code source est la propriété exclusive de **Souplesse Fitness** et de ses développeurs. 

### ❌ Interdictions strictes :
- **Duplication** ou copie non autorisée du code
- **Redistribution** sous quelque forme que ce soit
- **Utilisation commerciale** sans permission écrite
- **Modification** et redistribution sans autorisation
- **Reverse engineering** à des fins commerciales

### ✅ Utilisation autorisée :
- **Consultation** du code à des fins éducatives uniquement
- **Contribution** au projet via des Pull Requests approuvées
- **Fork** pour contribution personnelle (non commerciale)

### 📧 Demande d'autorisation :
Pour toute utilisation commerciale ou duplication, contactez-nous à : **souplessefitness@hotmail.fr**

**Toute violation de ces droits sera poursuivie conformément à la législation en vigueur.**

---

## 🎯 À propos

Souplesse Fitness est une salle de sport moderne située au Bénin (Face Clôture Iita, Cotonou). Cette application web permet de gérer l'ensemble des activités de la salle : inscriptions, abonnements, réservations de cours, coaching personnalisé, boutique et administration.

## ✨ Fonctionnalités

### 👥 Gestion des utilisateurs
- **Clients** : Inscription, gestion du profil, abonnements, réservations
- **Coachs** : Planning personnel, gestion des clients, séances de coaching
- **Administrateurs** : Gestion complète de la salle et des utilisateurs

### 🏋️ Services proposés
- **Abonnements** : Différentes formules d'abonnement
- **Cours collectifs** : Fit Dance, Taekwondo, Boxe, etc.
- **Coaching personnalisé** : Séances individuelles avec nos coachs
- **Boutique** : Vente d'articles de sport et compléments
- **Contact** : Formulaire de contact intégré

### 📱 Interface moderne
- Design responsive avec Bootstrap 5
- Interface utilisateur intuitive
- Système de notifications flash
- Navigation adaptée selon le rôle utilisateur

## 🛠️ Technologies utilisées

### Backend
- **PHP 8.2**
- **Symfony 7.3.1**
- **Doctrine ORM 3.5.0**
- **MySQL** pour la base de données
- **Twig** pour les templates
- **PHPUnit** pour les tests

### Frontend
- **Bootstrap 5.3.7**
- **Webpack Encore 5.0.0**
- **Stimulus 3.2.2**
- **Bootstrap Icons 1.13.1**
- **Animate.css 4.1.1**

### Outils de développement
- **Docker** avec compose
- **Webpack** pour la compilation des assets
- **npm** pour la gestion des packages JavaScript

## 📋 Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js et npm
- MySQL
- Docker (optionnel mais recommandé)

## 🚀 Installation

### 1. Cloner le projet

git clone [https://github.com/votre-username/souplesseFitness.git](https://github.com/votre-username/souplesseFitness.git) 
cd souplesseFitness


### 2. Installation des dépendances PHP
composer install

### 3. Installation des dépendances JavaScript
npm install


### 4. Configuration de l'environnement
cp .env .env.local
Modifiez le fichier `.env.local` avec vos paramètres de base de données.

### 5. Création de la base de données
php bin/console doctrine:database:create 
php bin/console doctrine:migrations:migrate



### 6. Chargement des données de test (optionnel)
```bash
php bin/console doctrine:fixtures:load
```

### 7. Compilation des assets
npm run build


### 8. ### Lancement du serveur de développement
symfony server:start





## 🐳 Installation avec Docker

# Démarrer les services
docker-compose up -d

# Installer les dépendances
docker-compose exec app composer install
docker-compose exec app npm install

# Configurer la base de données
docker-compose exec app php bin/console doctrine:database:create
docker-compose exec app php bin/console doctrine:migrations:migrate
docker-compose exec app php bin/console doctrine:fixtures:load

# Compiler les assets
docker-compose exec app npm run build


## 📁 Structure du projet


souplesseFitness/
├── assets/                 # Assets frontend (JS, CSS)
│   ├── controllers/        # Contrôleurs Stimulus
│   ├── js/                # JavaScript personnalisé
│   └── styles/            # Styles CSS
├── src/
│   ├── Controller/        # Contrôleurs Symfony
│   ├── Entity/           # Entités Doctrine
│   ├── Form/             # Types de formulaires
│   ├── Repository/       # Repositories Doctrine
│   └── Security/         # Classes de sécurité
├── templates/            # Templates Twig
├── migrations/           # Migrations de base de données
├── tests/               # Tests PHPUnit
└── public/              # Point d'entrée web



## 🎭 Rôles utilisateurs
### 👑 Administrateur
- Tableau de bord complet
- Gestion des utilisateurs
- Configuration de la salle
- Statistiques

### 💪 Coach
- Planning personnel
- Gestion des clients assignés
- Séances de coaching
- Suivi des performances

### 🏃 Client
- Profil personnel
- Abonnements actifs
- Réservation de cours
- Historique des activités

## 🧪 Tests
Lancer les tests unitaires :


php bin/phpunit

## 📦 Déploiement
### Production

# Optimisation Composer
composer install --no-dev --optimize-autoloader

# Compilation des assets pour la production
npm run build

# Vider le cache
php bin/console cache:clear --env=prod

# Optimisation Composer
composer install --no-dev --optimize-autoloader

# Compilation des assets pour la production
npm run build

# Vider le cache
php bin/console cache:clear --env=prod


## 📞 Contact & Informations pratiques
**Souplesse Fitness**
- 📍 Face Clôture Iita au bord des pavés, Cotonou, Bénin
- 📞 +229 01 96 11 61 36 / +229 01 96 77 35 09
- 📧 souplessefitness@hotmail.fr

### 🕒 Horaires d'ouverture
- **Lundi au Vendredi** : 7h - 22h
- **Samedi** : 7h - 20h
- **Dimanche & Jours fériés** : 7h - 14h

### 👕 Dress Code
- ✅ Tenue de sport obligatoire
- ✅ Chaussures de sport
- ✅ Serviette obligatoire

## 🤝 Contribution
Les contributions sont les bienvenues ! N'hésitez pas à :
1. Fork le projet
2. Créer une branche pour votre fonctionnalité (`git checkout -b feature/nouvelle-fonctionnalite`)
3. Commiter vos changements (`git commit -am 'Ajout d'une nouvelle fonctionnalité'`)
4. Pousser vers la branche (`git push origin feature/nouvelle-fonctionnalite`)
5. Créer une Pull Request

## 📄 Licence
Ce projet est sous licence [MIT](LICENSE).
## 🙏 Remerciements
- L'équipe de Souplesse Fitness
- La communauté Symfony
- Tous les contributeurs du projet

**Développé pour Souplesse Fitness**
_STABILITÉ - PROGRÈS - RÉUSSITE_
