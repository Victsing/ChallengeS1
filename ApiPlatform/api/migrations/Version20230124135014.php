<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124135014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ALTER size_id SET NOT NULL');
        $this->addSql('ALTER TABLE company ALTER revenue_id SET NOT NULL');
        $this->addSql('ALTER TABLE company ALTER sector_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE company ALTER size_id DROP NOT NULL');
        $this->addSql('ALTER TABLE company ALTER revenue_id DROP NOT NULL');
        $this->addSql('ALTER TABLE company ALTER sector_id DROP NOT NULL');
    }
}
