<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221163727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE business_model_canvas (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', project_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', key_partners LONGTEXT NOT NULL, key_activities LONGTEXT NOT NULL, key_resources LONGTEXT NOT NULL, value_propositions LONGTEXT NOT NULL, customer_relationships LONGTEXT NOT NULL, channels LONGTEXT NOT NULL, customer_segments LONGTEXT NOT NULL, cost_structure LONGTEXT NOT NULL, revenue_streams LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C5506534166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE business_model_canvas ADD CONSTRAINT FK_C5506534166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_model_canvas DROP FOREIGN KEY FK_C5506534166D1F9C');
        $this->addSql('DROP TABLE business_model_canvas');
    }
}
