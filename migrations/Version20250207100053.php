<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207100053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate ADD firstname VARCHAR(255) NOT NULL, ADD lastname VARCHAR(255) NOT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE gender_id gender_id INT DEFAULT NULL, CHANGE place_of_birth birthplace VARCHAR(255) DEFAULT NULL, CHANGE date_of_birth birthdate DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate DROP firstname, DROP lastname, CHANGE user_id user_id INT NOT NULL, CHANGE gender_id gender_id INT NOT NULL, CHANGE birthplace place_of_birth VARCHAR(255) DEFAULT NULL, CHANGE birthdate date_of_birth DATE NOT NULL');
    }
}
