<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211151911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE availability (id INT AUTO_INCREMENT NOT NULL, state_id INT NOT NULL, INDEX IDX_3FB7A2BF5D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, gender_id INT DEFAULT NULL, job_category_id INT DEFAULT NULL, category_id INT DEFAULT NULL, experience_id INT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, profile_picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_name VARCHAR(255) DEFAULT NULL, current_location VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, nationality VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', birth_place VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, passport VARCHAR(255) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C8B28E44A76ED395 (user_id), INDEX IDX_C8B28E44708A0E0 (gender_id), INDEX IDX_C8B28E44712A86AB (job_category_id), INDEX IDX_C8B28E4412469DE2 (category_id), INDEX IDX_C8B28E4446E90E27 (experience_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_candidate (category_id INT NOT NULL, candidate_id INT NOT NULL, INDEX IDX_FD25C7412469DE2 (category_id), INDEX IDX_FD25C7491BD8781 (candidate_id), PRIMARY KEY(category_id, candidate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gender (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer_type_category (job_offer_type_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_6548BE78CF9A1456 (job_offer_type_id), INDEX IDX_6548BE7812469DE2 (category_id), PRIMARY KEY(job_offer_type_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passport_state (id INT AUTO_INCREMENT NOT NULL, state VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE availability ADD CONSTRAINT FK_3FB7A2BF5D83CC1 FOREIGN KEY (state_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44712A86AB FOREIGN KEY (job_category_id) REFERENCES job_offer_type (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E4412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E4446E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('ALTER TABLE category_candidate ADD CONSTRAINT FK_FD25C7412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_candidate ADD CONSTRAINT FK_FD25C7491BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_offer_type_category ADD CONSTRAINT FK_6548BE78CF9A1456 FOREIGN KEY (job_offer_type_id) REFERENCES job_offer_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_offer_type_category ADD CONSTRAINT FK_6548BE7812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availability DROP FOREIGN KEY FK_3FB7A2BF5D83CC1');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44A76ED395');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44708A0E0');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44712A86AB');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E4412469DE2');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E4446E90E27');
        $this->addSql('ALTER TABLE category_candidate DROP FOREIGN KEY FK_FD25C7412469DE2');
        $this->addSql('ALTER TABLE category_candidate DROP FOREIGN KEY FK_FD25C7491BD8781');
        $this->addSql('ALTER TABLE job_offer_type_category DROP FOREIGN KEY FK_6548BE78CF9A1456');
        $this->addSql('ALTER TABLE job_offer_type_category DROP FOREIGN KEY FK_6548BE7812469DE2');
        $this->addSql('DROP TABLE availability');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_candidate');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE gender');
        $this->addSql('DROP TABLE job_offer_type');
        $this->addSql('DROP TABLE job_offer_type_category');
        $this->addSql('DROP TABLE passport_state');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
