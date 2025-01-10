<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109170450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competitor_analysis ADD x_axis_label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE competitor_analysis ADD y_axis_label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE competitor_analysis ADD competitors JSON NOT NULL');
        $this->addSql('ALTER TABLE competitor_analysis ADD our_position JSON NOT NULL');
        $this->addSql('ALTER TABLE golden_triangle ADD our_position JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE golden_triangle DROP our_position');
        $this->addSql('ALTER TABLE competitor_analysis DROP x_axis_label');
        $this->addSql('ALTER TABLE competitor_analysis DROP y_axis_label');
        $this->addSql('ALTER TABLE competitor_analysis DROP competitors');
        $this->addSql('ALTER TABLE competitor_analysis DROP our_position');
    }
}
