<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021165644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE refresh_tokens ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE team ADD deletable BOOLEAN DEFAULT true');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq');
        $this->addSql('SELECT setval(\'refresh_tokens_id_seq\', (SELECT MAX(id) FROM refresh_tokens))');
        $this->addSql('ALTER TABLE refresh_tokens ALTER id SET DEFAULT nextval(\'refresh_tokens_id_seq\')');
        $this->addSql('ALTER TABLE team DROP deletable');
    }
}
