<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223165530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pestel (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', project_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', political LONGTEXT NOT NULL, economic LONGTEXT NOT NULL, social LONGTEXT NOT NULL, technological LONGTEXT NOT NULL, environmental LONGTEXT NOT NULL, legal LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9DCE2930166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pestel ADD CONSTRAINT FK_9DCE2930166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pestel DROP FOREIGN KEY FK_9DCE2930166D1F9C');
        $this->addSql('DROP TABLE pestel');
    }
}
