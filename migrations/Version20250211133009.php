<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211133009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate ADD first_name VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD birth_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD birth_place VARCHAR(255) DEFAULT NULL, ADD notes LONGTEXT DEFAULT NULL, DROP birthplace, DROP birthdate, DROP adress, DROP firstname, DROP lastname, DROP progression, CHANGE user_id user_id INT NOT NULL, CHANGE passport_state_id job_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44712A86AB FOREIGN KEY (job_category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_C8B28E44712A86AB ON candidate (job_category_id)');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44712A86AB');
        $this->addSql('DROP INDEX IDX_C8B28E44712A86AB ON candidate');
        $this->addSql('ALTER TABLE candidate ADD birthplace VARCHAR(255) DEFAULT NULL, ADD birthdate DATE DEFAULT NULL, ADD adress VARCHAR(255) DEFAULT NULL, ADD firstname VARCHAR(255) DEFAULT NULL, ADD lastname VARCHAR(255) DEFAULT NULL, ADD progression INT DEFAULT 0 NOT NULL, DROP first_name, DROP last_name, DROP address, DROP birth_date, DROP birth_place, DROP notes, CHANGE user_id user_id INT DEFAULT NULL, CHANGE job_category_id passport_state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP reset_token');
    }
}
