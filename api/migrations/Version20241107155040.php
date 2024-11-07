<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107155040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team ADD subscription_chooser_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN team.subscription_chooser_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FC63C9A0 FOREIGN KEY (subscription_chooser_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C4E0A61FC63C9A0 ON team (subscription_chooser_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61FC63C9A0');
        $this->addSql('DROP INDEX IDX_C4E0A61FC63C9A0');
        $this->addSql('ALTER TABLE team DROP subscription_chooser_id');
    }
}
