<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212144519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offer_type ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE job_offer_type ADD CONSTRAINT FK_1851E92319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1851E923989D9B62 ON job_offer_type (slug)');
        $this->addSql('CREATE INDEX IDX_1851E92319EB6921 ON job_offer_type (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offer_type DROP FOREIGN KEY FK_1851E92319EB6921');
        $this->addSql('DROP INDEX UNIQ_1851E923989D9B62 ON job_offer_type');
        $this->addSql('DROP INDEX IDX_1851E92319EB6921 ON job_offer_type');
        $this->addSql('ALTER TABLE job_offer_type DROP slug');
    }
}
