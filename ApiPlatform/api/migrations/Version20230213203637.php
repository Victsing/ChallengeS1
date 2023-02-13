<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213203637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT FK_FE38F84491BD8781');
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT FK_FE38F844BE04EA9');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84491BD8781 FOREIGN KEY (candidate_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844BE04EA9 FOREIGN KEY (job_id) REFERENCES job_ads (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F19113B3C');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F19113B3C FOREIGN KEY (founder_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT fk_fe38f84491bd8781');
        $this->addSql('ALTER TABLE appointment DROP CONSTRAINT fk_fe38f844be04ea9');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT fk_fe38f84491bd8781 FOREIGN KEY (candidate_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT fk_fe38f844be04ea9 FOREIGN KEY (job_id) REFERENCES job_ads (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT fk_4fbf094f19113b3c');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT fk_4fbf094f19113b3c FOREIGN KEY (founder_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
