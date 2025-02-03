<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241203084815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription_price ADD user_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription_price ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE subscription_price ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN subscription_price.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription_price ADD CONSTRAINT FK_30F023AAA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_30F023AAA76ED395 ON subscription_price (user_id)');
        $this->addSql('ALTER TABLE team ALTER subscription_price_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN team.subscription_price_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription DROP recurrence');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team ALTER subscription_price_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN team.subscription_price_id IS NULL');
        $this->addSql('ALTER TABLE subscription_price DROP CONSTRAINT FK_30F023AAA76ED395');
        $this->addSql('DROP INDEX IDX_30F023AAA76ED395');
        $this->addSql('ALTER TABLE subscription_price DROP user_id');
        $this->addSql('ALTER TABLE subscription_price DROP created_at');
        $this->addSql('ALTER TABLE subscription_price DROP updated_at');
        $this->addSql('ALTER TABLE subscription ADD recurrence VARCHAR(255) NOT NULL');
    }
}
