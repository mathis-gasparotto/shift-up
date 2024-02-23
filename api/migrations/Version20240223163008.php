<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223163008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE buyer_persona (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', project_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', personal_info LONGTEXT NOT NULL, professional_info LONGTEXT NOT NULL, goals_challenges LONGTEXT NOT NULL, communication_channels LONGTEXT NOT NULL, values_fears LONGTEXT NOT NULL, negative_info LONGTEXT NOT NULL, INDEX IDX_F1491FED166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buyer_persona ADD CONSTRAINT FK_F1491FED166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE buyer_persona DROP FOREIGN KEY FK_F1491FED166D1F9C');
        $this->addSql('DROP TABLE buyer_persona');
    }
}
