<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209190003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT NOT NULL, founder_id INT NOT NULL, size_id INT NOT NULL, revenue_id INT NOT NULL, sector_id INT NOT NULL, name VARCHAR(255) NOT NULL, creation_date DATE NOT NULL, address VARCHAR(255) NOT NULL, website VARCHAR(100) DEFAULT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, siret BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4FBF094F19113B3C ON company (founder_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F498DA827 ON company (size_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F224718EB ON company (revenue_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FDE95C867 ON company (sector_id)');
        $this->addSql('COMMENT ON COLUMN company.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE company_revenue_options (id INT NOT NULL, revenue VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE company_size_options (id INT NOT NULL, size VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE customer (id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE detail (id INT NOT NULL, order_delivery_id INT DEFAULT NULL, pizza_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, size VARCHAR(2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2E067F939A116A7 ON detail (order_delivery_id)');
        $this->addSql('CREATE INDEX IDX_2E067F93D41D1D42 ON detail (pizza_id)');
        $this->addSql('CREATE TABLE ingredient (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE job_ads_user (job_ads_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(job_ads_id, user_id))');
        $this->addSql('CREATE INDEX IDX_FAFF9CE1AE569200 ON job_ads_user (job_ads_id)');
        $this->addSql('CREATE INDEX IDX_FAFF9CE1A76ED395 ON job_ads_user (user_id)');
        $this->addSql('CREATE TABLE offer (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, type_contrat VARCHAR(50) NOT NULL, duration VARCHAR(50) DEFAULT NULL, year_experience INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, total DOUBLE PRECISION DEFAULT \'0\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pizza (id INT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CFDD826F7E3C61F9 ON pizza (owner_id)');
        $this->addSql('CREATE TABLE pizza_ingredient (pizza_id INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(pizza_id, ingredient_id))');
        $this->addSql('CREATE INDEX IDX_6FF6C03FD41D1D42 ON pizza_ingredient (pizza_id)');
        $this->addSql('CREATE INDEX IDX_6FF6C03F933FE08C ON pizza_ingredient (ingredient_id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F19113B3C FOREIGN KEY (founder_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F498DA827 FOREIGN KEY (size_id) REFERENCES company_size_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F224718EB FOREIGN KEY (revenue_id) REFERENCES company_revenue_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FDE95C867 FOREIGN KEY (sector_id) REFERENCES company_sector_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail ADD CONSTRAINT FK_2E067F939A116A7 FOREIGN KEY (order_delivery_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail ADD CONSTRAINT FK_2E067F93D41D1D42 FOREIGN KEY (pizza_id) REFERENCES pizza (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job_ads_user ADD CONSTRAINT FK_FAFF9CE1AE569200 FOREIGN KEY (job_ads_id) REFERENCES job_ads (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job_ads_user ADD CONSTRAINT FK_FAFF9CE1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pizza ADD CONSTRAINT FK_CFDD826F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pizza_ingredient ADD CONSTRAINT FK_6FF6C03FD41D1D42 FOREIGN KEY (pizza_id) REFERENCES pizza (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pizza_ingredient ADD CONSTRAINT FK_6FF6C03F933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job_ads ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE job_ads DROP location');
        $this->addSql('ALTER TABLE job_ads DROP job_ad_status');
        $this->addSql('ALTER TABLE job_ads ALTER salary TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE job_ads ALTER salary DROP DEFAULT');
        $this->addSql('ALTER TABLE job_ads ADD CONSTRAINT FK_BA1C65B1979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BA1C65B1979B1AD6 ON job_ads (company_id)');
        $this->addSql('ALTER TABLE "user" ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE "user" ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE job_ads DROP CONSTRAINT FK_BA1C65B1979B1AD6');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F224718EB');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F498DA827');
        $this->addSql('ALTER TABLE pizza_ingredient DROP CONSTRAINT FK_6FF6C03F933FE08C');
        $this->addSql('ALTER TABLE detail DROP CONSTRAINT FK_2E067F939A116A7');
        $this->addSql('ALTER TABLE detail DROP CONSTRAINT FK_2E067F93D41D1D42');
        $this->addSql('ALTER TABLE pizza_ingredient DROP CONSTRAINT FK_6FF6C03FD41D1D42');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_revenue_options');
        $this->addSql('DROP TABLE company_size_options');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE detail');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE job_ads_user');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE pizza');
        $this->addSql('DROP TABLE pizza_ingredient');
        $this->addSql('DROP INDEX IDX_BA1C65B1979B1AD6');
        $this->addSql('ALTER TABLE job_ads ADD location VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE job_ads ADD job_ad_status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE job_ads DROP company_id');
        $this->addSql('ALTER TABLE job_ads ALTER salary TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE job_ads ALTER salary DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE "user" ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS NULL');
    }
}
