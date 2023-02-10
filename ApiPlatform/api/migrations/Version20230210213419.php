<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210213419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE appointment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE company_revenue_options_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE company_sector_options_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE company_size_options_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE job_ads_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE offer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE appointment (id INT NOT NULL, candidate_id INT NOT NULL, job_id INT NOT NULL, time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, accepted BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE38F84491BD8781 ON appointment (candidate_id)');
        $this->addSql('CREATE INDEX IDX_FE38F844BE04EA9 ON appointment (job_id)');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, founder_id INT NOT NULL, size_id INT NOT NULL, revenue_id INT NOT NULL, sector_id INT NOT NULL, name VARCHAR(255) NOT NULL, creation_date DATE NOT NULL, address VARCHAR(255) NOT NULL, website VARCHAR(100) DEFAULT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, siret BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4FBF094F19113B3C ON company (founder_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F498DA827 ON company (size_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F224718EB ON company (revenue_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FDE95C867 ON company (sector_id)');
        $this->addSql('COMMENT ON COLUMN company.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE company_revenue_options (id INT NOT NULL, revenue VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE company_sector_options (id INT NOT NULL, sector VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE company_size_options (id INT NOT NULL, size VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE job_ads (id INT NOT NULL, company_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, contract_type VARCHAR(255) NOT NULL, salary VARCHAR(255) NOT NULL, mission_duration VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BA1C65B1979B1AD6 ON job_ads (company_id)');
        $this->addSql('COMMENT ON COLUMN job_ads.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN job_ads.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE job_ads_user (job_ads_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(job_ads_id, user_id))');
        $this->addSql('CREATE INDEX IDX_FAFF9CE1AE569200 ON job_ads_user (job_ads_id)');
        $this->addSql('CREATE INDEX IDX_FAFF9CE1A76ED395 ON job_ads_user (user_id)');
        $this->addSql('CREATE TABLE offer (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, type_contrat VARCHAR(50) NOT NULL, duration VARCHAR(50) DEFAULT NULL, year_experience INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, token VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthdate VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84491BD8781 FOREIGN KEY (candidate_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844BE04EA9 FOREIGN KEY (job_id) REFERENCES job_ads (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F19113B3C FOREIGN KEY (founder_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F498DA827 FOREIGN KEY (size_id) REFERENCES company_size_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F224718EB FOREIGN KEY (revenue_id) REFERENCES company_revenue_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FDE95C867 FOREIGN KEY (sector_id) REFERENCES company_sector_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job_ads ADD CONSTRAINT FK_BA1C65B1979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job_ads_user ADD CONSTRAINT FK_FAFF9CE1AE569200 FOREIGN KEY (job_ads_id) REFERENCES job_ads (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job_ads_user ADD CONSTRAINT FK_FAFF9CE1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE job_ads DROP CONSTRAINT FK_BA1C65B1979B1AD6');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F224718EB');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094FDE95C867');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F498DA827');
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT FK_FE38F844BE04EA9');
        $this->addSql('ALTER TABLE job_ads_user DROP CONSTRAINT FK_FAFF9CE1AE569200');
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT FK_FE38F84491BD8781');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F19113B3C');
        $this->addSql('ALTER TABLE job_ads_user DROP CONSTRAINT FK_FAFF9CE1A76ED395');
        $this->addSql('DROP SEQUENCE appointment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE company_revenue_options_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE company_sector_options_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE company_size_options_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE job_ads_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE offer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_revenue_options');
        $this->addSql('DROP TABLE company_sector_options');
        $this->addSql('DROP TABLE company_size_options');
        $this->addSql('DROP TABLE job_ads');
        $this->addSql('DROP TABLE job_ads_user');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE "user"');
    }
}
