<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721014412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, type VARCHAR(50) NOT NULL, prix INT NOT NULL, duree_jours INT NOT NULL, description LONGTEXT DEFAULT NULL, actif TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_cours (client_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_5EA7102B19EB6921 (client_id), INDEX IDX_5EA7102B7ECF78B0 (cours_id), PRIMARY KEY(client_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coach (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, specialites LONGTEXT DEFAULT NULL, actif TINYINT(1) NOT NULL, date_embauche DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, coach_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, capacite_max INT NOT NULL, date_heure DATETIME NOT NULL, duree_minutes INT NOT NULL, actif TINYINT(1) NOT NULL, INDEX IDX_FDCA8C9C3C105691 (coach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance_coaching (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, coach_id INT DEFAULT NULL, date_heure DATETIME NOT NULL, duree_minutes INT NOT NULL, type VARCHAR(50) NOT NULL, statut VARCHAR(30) NOT NULL, notes LONGTEXT DEFAULT NULL, prix_seance INT DEFAULT NULL, INDEX IDX_260588B619EB6921 (client_id), INDEX IDX_260588B63C105691 (coach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_cours ADD CONSTRAINT FK_5EA7102B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_cours ADD CONSTRAINT FK_5EA7102B7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C3C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE seance_coaching ADD CONSTRAINT FK_260588B619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE seance_coaching ADD CONSTRAINT FK_260588B63C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE client ADD coach_id INT DEFAULT NULL, ADD abonnement_actuel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404553C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455116BAC33 FOREIGN KEY (abonnement_actuel_id) REFERENCES abonnement (id)');
        $this->addSql('CREATE INDEX IDX_C74404553C105691 ON client (coach_id)');
        $this->addSql('CREATE INDEX IDX_C7440455116BAC33 ON client (abonnement_actuel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455116BAC33');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404553C105691');
        $this->addSql('ALTER TABLE client_cours DROP FOREIGN KEY FK_5EA7102B19EB6921');
        $this->addSql('ALTER TABLE client_cours DROP FOREIGN KEY FK_5EA7102B7ECF78B0');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C3C105691');
        $this->addSql('ALTER TABLE seance_coaching DROP FOREIGN KEY FK_260588B619EB6921');
        $this->addSql('ALTER TABLE seance_coaching DROP FOREIGN KEY FK_260588B63C105691');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE client_cours');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE seance_coaching');
        $this->addSql('DROP INDEX IDX_C74404553C105691 ON client');
        $this->addSql('DROP INDEX IDX_C7440455116BAC33 ON client');
        $this->addSql('ALTER TABLE client DROP coach_id, DROP abonnement_actuel_id');
    }
}
