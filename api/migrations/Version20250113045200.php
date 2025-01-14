<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113045200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team ADD scheduled_subscription_price_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN team.scheduled_subscription_price_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F84F1DCA8 FOREIGN KEY (scheduled_subscription_price_id) REFERENCES subscription_price (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C4E0A61F84F1DCA8 ON team (scheduled_subscription_price_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F84F1DCA8');
        $this->addSql('DROP INDEX IDX_C4E0A61F84F1DCA8');
        $this->addSql('ALTER TABLE team DROP scheduled_subscription_price_id');
    }
}
