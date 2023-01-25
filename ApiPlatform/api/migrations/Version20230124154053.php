<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124154053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_ads_user (job_ads_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(job_ads_id, user_id))');
        $this->addSql('CREATE INDEX IDX_FAFF9CE1AE569200 ON job_ads_user (job_ads_id)');
        $this->addSql('CREATE INDEX IDX_FAFF9CE1A76ED395 ON job_ads_user (user_id)');
        $this->addSql('ALTER TABLE job_ads_user ADD CONSTRAINT FK_FAFF9CE1AE569200 FOREIGN KEY (job_ads_id) REFERENCES job_ads (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job_ads_user ADD CONSTRAINT FK_FAFF9CE1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE job_ads_user');
    }
}
