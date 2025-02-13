<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213081624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1851E923989D9B62 ON job_offer_type (slug)');
        $this->addSql('ALTER TABLE job_offer_type RENAME INDEX fk_1851e92319eb6921 TO IDX_1851E92319EB6921');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1851E923989D9B62 ON job_offer_type');
        $this->addSql('ALTER TABLE job_offer_type RENAME INDEX idx_1851e92319eb6921 TO FK_1851E92319EB6921');
        $this->addSql('ALTER TABLE category DROP slug');
    }
}
