<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230123005412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ADD size_id INT NOT NULL');
        $this->addSql('ALTER TABLE company ADD revenue_id INT NOT NULL');
        $this->addSql('ALTER TABLE company ADD sector_id INT NOT NULL');
        $this->addSql('ALTER TABLE company DROP size');
        $this->addSql('ALTER TABLE company DROP revenues');
        $this->addSql('ALTER TABLE company DROP sector');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F498DA827 FOREIGN KEY (size_id) REFERENCES company_size_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F224718EB FOREIGN KEY (revenue_id) REFERENCES company_revenue_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FDE95C867 FOREIGN KEY (sector_id) REFERENCES company_sector_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4FBF094F498DA827 ON company (size_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F224718EB ON company (revenue_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FDE95C867 ON company (sector_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F498DA827');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F224718EB');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094FDE95C867');
        $this->addSql('DROP INDEX IDX_4FBF094F498DA827');
        $this->addSql('DROP INDEX IDX_4FBF094F224718EB');
        $this->addSql('DROP INDEX IDX_4FBF094FDE95C867');
        $this->addSql('ALTER TABLE company ADD size VARCHAR(25) NOT NULL');
        $this->addSql('ALTER TABLE company ADD revenues VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE company ADD sector VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE company DROP size_id');
        $this->addSql('ALTER TABLE company DROP revenue_id');
        $this->addSql('ALTER TABLE company DROP sector_id');
    }
}
