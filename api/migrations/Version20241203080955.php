<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241203080955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription_price (id UUID NOT NULL, subscription_id UUID NOT NULL, price INT NOT NULL DEFAULT 0, recurrence VARCHAR(255) NOT NULL, stripe_price_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_30F023AA9A1887DC ON subscription_price (subscription_id)');
        $this->addSql('COMMENT ON COLUMN subscription_price.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN subscription_price.subscription_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription_price ADD CONSTRAINT FK_30F023AA9A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription DROP stripe_price_id');
        $this->addSql('ALTER TABLE subscription DROP price');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT fk_c4e0a61f9a1887dc');
        $this->addSql('DROP INDEX idx_c4e0a61f9a1887dc');
        $this->addSql('ALTER TABLE team DROP subscription_id');
        $this->addSql('ALTER TABLE team ADD subscription_price_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FB1BA98CB FOREIGN KEY (subscription_price_id) REFERENCES subscription_price (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C4E0A61FB1BA98CB ON team (subscription_price_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61FB1BA98CB');
        $this->addSql('ALTER TABLE subscription_price DROP CONSTRAINT FK_30F023AA9A1887DC');
        $this->addSql('DROP TABLE subscription_price');
        $this->addSql('DROP INDEX IDX_C4E0A61FB1BA98CB');
        $this->addSql('ALTER TABLE team RENAME COLUMN subscription_price_id TO subscription_id');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT fk_c4e0a61f9a1887dc FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c4e0a61f9a1887dc ON team (subscription_id)');
        $this->addSql('ALTER TABLE subscription ADD stripe_price_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription ADD price INT DEFAULT 0 NOT NULL DEFAULT 0');
    }
}
