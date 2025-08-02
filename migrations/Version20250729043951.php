<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250729043951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(100) NOT NULL, ADD last_name VARCHAR(100) NOT NULL, ADD phone VARCHAR(20) NOT NULL, ADD birth_date DATE NOT NULL, ADD gender VARCHAR(10) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD city VARCHAR(100) NOT NULL, ADD emergency_contact VARCHAR(100) DEFAULT NULL, ADD emergency_phone VARCHAR(20) DEFAULT NULL, ADD fitness_goals VARCHAR(50) DEFAULT NULL, ADD experience_level VARCHAR(20) DEFAULT NULL, ADD medical_conditions LONGTEXT DEFAULT NULL, ADD subscribe_newsletter TINYINT(1) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD last_login DATETIME DEFAULT NULL, ADD is_active TINYINT(1) NOT NULL, DROP nom, DROP prenom');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_identifier_email TO UNIQ_8D93D649E7927C74');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` ADD prenom VARCHAR(100) DEFAULT NULL, DROP first_name, DROP last_name, DROP phone, DROP birth_date, DROP gender, DROP address, DROP city, DROP emergency_phone, DROP fitness_goals, DROP experience_level, DROP medical_conditions, DROP subscribe_newsletter, DROP created_at, DROP last_login, DROP is_active, CHANGE emergency_contact nom VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` RENAME INDEX uniq_8d93d649e7927c74 TO UNIQ_IDENTIFIER_EMAIL');
    }
}
