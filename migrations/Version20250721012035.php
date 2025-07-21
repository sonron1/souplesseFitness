<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721012035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD numero_membre VARCHAR(50) NOT NULL, ADD points_fidelite INT NOT NULL, ADD adresse LONGTEXT DEFAULT NULL, ADD profession VARCHAR(100) DEFAULT NULL, ADD contact_urgence VARCHAR(100) DEFAULT NULL, ADD photo VARCHAR(255) DEFAULT NULL, ADD date_debut_abonnement DATETIME DEFAULT NULL, ADD date_fin_abonnement DATETIME DEFAULT NULL, ADD type_abonnement VARCHAR(50) DEFAULT NULL, ADD notes LONGTEXT DEFAULT NULL, ADD date_visite DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP numero_membre, DROP points_fidelite, DROP adresse, DROP profession, DROP contact_urgence, DROP photo, DROP date_debut_abonnement, DROP date_fin_abonnement, DROP type_abonnement, DROP notes, DROP date_visite');
    }
}
