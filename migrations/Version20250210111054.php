<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210111054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidate ADD job_category_id INT DEFAULT NULL, ADD experience_id INT DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD current_location VARCHAR(255) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD country VARCHAR(255) DEFAULT NULL, ADD nationality VARCHAR(255) DEFAULT NULL, ADD birth_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD birth_place VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD notes LONGTEXT DEFAULT NULL, ADD passport VARCHAR(255) DEFAULT NULL, ADD cv VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44712A86AB FOREIGN KEY (job_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E4446E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('CREATE INDEX IDX_C8B28E44712A86AB ON candidate (job_category_id)');
        $this->addSql('CREATE INDEX IDX_C8B28E4446E90E27 ON candidate (experience_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44712A86AB');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E4446E90E27');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE job_offer_type');
        $this->addSql('DROP INDEX IDX_C8B28E44712A86AB ON candidate');
        $this->addSql('DROP INDEX IDX_C8B28E4446E90E27 ON candidate');
        $this->addSql('ALTER TABLE candidate DROP job_category_id, DROP experience_id, DROP last_name, DROP current_location, DROP address, DROP country, DROP nationality, DROP birth_date, DROP birth_place, DROP description, DROP notes, DROP passport, DROP cv');
    }
}