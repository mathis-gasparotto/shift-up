<?php

declare(strict_types=1);

namespace DoctrineMigrations\MySQL;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004164412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_model_canvas ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE business_model_canvas ADD CONSTRAINT FK_C5506534A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C5506534A76ED395 ON business_model_canvas (user_id)');
        $this->addSql('ALTER TABLE buyer_persona ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE buyer_persona ADD CONSTRAINT FK_F1491FEDA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_F1491FEDA76ED395 ON buyer_persona (user_id)');
        $this->addSql('ALTER TABLE competitor_analysis ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE competitor_analysis ADD CONSTRAINT FK_6861F269A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_6861F269A76ED395 ON competitor_analysis (user_id)');
        $this->addSql('ALTER TABLE golden_triangle ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE golden_triangle ADD CONSTRAINT FK_924B74E3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_924B74E3A76ED395 ON golden_triangle (user_id)');
        $this->addSql('ALTER TABLE marketing_mix4 ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE marketing_mix4 ADD CONSTRAINT FK_EB7BE57EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_EB7BE57EA76ED395 ON marketing_mix4 (user_id)');
        $this->addSql('ALTER TABLE marketing_mix5 ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE marketing_mix5 ADD CONSTRAINT FK_9C7CD5E8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_9C7CD5E8A76ED395 ON marketing_mix5 (user_id)');
        $this->addSql('ALTER TABLE media_object ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_14D43132A76ED395 ON media_object (user_id)');
        $this->addSql('ALTER TABLE pestel ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE pestel ADD CONSTRAINT FK_9DCE2930A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_9DCE2930A76ED395 ON pestel (user_id)');
        $this->addSql('ALTER TABLE project ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEA76ED395 ON project (user_id)');
        $this->addSql('ALTER TABLE smart ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE smart ADD CONSTRAINT FK_B2684D9AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_B2684D9AA76ED395 ON smart (user_id)');
        $this->addSql('ALTER TABLE stp ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE stp ADD CONSTRAINT FK_B2A0C2D5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_B2A0C2D5A76ED395 ON stp (user_id)');
        $this->addSql('ALTER TABLE subscription ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_A3C664D3A76ED395 ON subscription (user_id)');
        $this->addSql('ALTER TABLE swot ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE swot ADD CONSTRAINT FK_BC7D6CF6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_BC7D6CF6A76ED395 ON swot (user_id)');
        $this->addSql('ALTER TABLE team ADD user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C4E0A61FA76ED395 ON team (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_model_canvas DROP FOREIGN KEY FK_C5506534A76ED395');
        $this->addSql('DROP INDEX IDX_C5506534A76ED395 ON business_model_canvas');
        $this->addSql('ALTER TABLE business_model_canvas DROP user_id');
        $this->addSql('ALTER TABLE buyer_persona DROP FOREIGN KEY FK_F1491FEDA76ED395');
        $this->addSql('DROP INDEX IDX_F1491FEDA76ED395 ON buyer_persona');
        $this->addSql('ALTER TABLE buyer_persona DROP user_id');
        $this->addSql('ALTER TABLE competitor_analysis DROP FOREIGN KEY FK_6861F269A76ED395');
        $this->addSql('DROP INDEX IDX_6861F269A76ED395 ON competitor_analysis');
        $this->addSql('ALTER TABLE competitor_analysis DROP user_id');
        $this->addSql('ALTER TABLE golden_triangle DROP FOREIGN KEY FK_924B74E3A76ED395');
        $this->addSql('DROP INDEX IDX_924B74E3A76ED395 ON golden_triangle');
        $this->addSql('ALTER TABLE golden_triangle DROP user_id');
        $this->addSql('ALTER TABLE marketing_mix4 DROP FOREIGN KEY FK_EB7BE57EA76ED395');
        $this->addSql('DROP INDEX IDX_EB7BE57EA76ED395 ON marketing_mix4');
        $this->addSql('ALTER TABLE marketing_mix4 DROP user_id');
        $this->addSql('ALTER TABLE marketing_mix5 DROP FOREIGN KEY FK_9C7CD5E8A76ED395');
        $this->addSql('DROP INDEX IDX_9C7CD5E8A76ED395 ON marketing_mix5');
        $this->addSql('ALTER TABLE marketing_mix5 DROP user_id');
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D43132A76ED395');
        $this->addSql('DROP INDEX IDX_14D43132A76ED395 ON media_object');
        $this->addSql('ALTER TABLE media_object DROP user_id');
        $this->addSql('ALTER TABLE pestel DROP FOREIGN KEY FK_9DCE2930A76ED395');
        $this->addSql('DROP INDEX IDX_9DCE2930A76ED395 ON pestel');
        $this->addSql('ALTER TABLE pestel DROP user_id');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('DROP INDEX IDX_2FB3D0EEA76ED395 ON project');
        $this->addSql('ALTER TABLE project DROP user_id');
        $this->addSql('ALTER TABLE smart DROP FOREIGN KEY FK_B2684D9AA76ED395');
        $this->addSql('DROP INDEX IDX_B2684D9AA76ED395 ON smart');
        $this->addSql('ALTER TABLE smart DROP user_id');
        $this->addSql('ALTER TABLE stp DROP FOREIGN KEY FK_B2A0C2D5A76ED395');
        $this->addSql('DROP INDEX IDX_B2A0C2D5A76ED395 ON stp');
        $this->addSql('ALTER TABLE stp DROP user_id');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('DROP INDEX IDX_A3C664D3A76ED395 ON subscription');
        $this->addSql('ALTER TABLE subscription DROP user_id');
        $this->addSql('ALTER TABLE swot DROP FOREIGN KEY FK_BC7D6CF6A76ED395');
        $this->addSql('DROP INDEX IDX_BC7D6CF6A76ED395 ON swot');
        $this->addSql('ALTER TABLE swot DROP user_id');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FA76ED395');
        $this->addSql('DROP INDEX IDX_C4E0A61FA76ED395 ON team');
        $this->addSql('ALTER TABLE team DROP user_id');
    }
}
