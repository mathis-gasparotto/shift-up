<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108082528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_model_canvas ADD file_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN business_model_canvas.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE business_model_canvas ADD CONSTRAINT FK_C550653493CB796C FOREIGN KEY (file_id) REFERENCES media_object (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C550653493CB796C ON business_model_canvas (file_id)');
        $this->addSql('ALTER TABLE buyer_persona ADD file_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN buyer_persona.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE buyer_persona ADD CONSTRAINT FK_F1491FED93CB796C FOREIGN KEY (file_id) REFERENCES media_object (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F1491FED93CB796C ON buyer_persona (file_id)');
        $this->addSql('ALTER TABLE competitor_analysis ADD file_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN competitor_analysis.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE competitor_analysis ADD CONSTRAINT FK_6861F26993CB796C FOREIGN KEY (file_id) REFERENCES media_object (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6861F26993CB796C ON competitor_analysis (file_id)');
        $this->addSql('ALTER TABLE golden_triangle ADD file_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN golden_triangle.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE golden_triangle ADD CONSTRAINT FK_924B74E393CB796C FOREIGN KEY (file_id) REFERENCES media_object (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_924B74E393CB796C ON golden_triangle (file_id)');
        $this->addSql('ALTER TABLE marketing_mix4 ADD file_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN marketing_mix4.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE marketing_mix4 ADD CONSTRAINT FK_EB7BE57E93CB796C FOREIGN KEY (file_id) REFERENCES media_object (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB7BE57E93CB796C ON marketing_mix4 (file_id)');
        $this->addSql('ALTER TABLE marketing_mix5 ADD file_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN marketing_mix5.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE marketing_mix5 ADD CONSTRAINT FK_9C7CD5E893CB796C FOREIGN KEY (file_id) REFERENCES media_object (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9C7CD5E893CB796C ON marketing_mix5 (file_id)');
        $this->addSql('ALTER TABLE pestel ADD file_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN pestel.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE pestel ADD CONSTRAINT FK_9DCE293093CB796C FOREIGN KEY (file_id) REFERENCES media_object (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9DCE293093CB796C ON pestel (file_id)');
        $this->addSql('ALTER TABLE smart ADD file_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN smart.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE smart ADD CONSTRAINT FK_B2684D9A93CB796C FOREIGN KEY (file_id) REFERENCES media_object (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B2684D9A93CB796C ON smart (file_id)');
        $this->addSql('ALTER TABLE stp ADD file_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN stp.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE stp ADD CONSTRAINT FK_B2A0C2D593CB796C FOREIGN KEY (file_id) REFERENCES media_object (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B2A0C2D593CB796C ON stp (file_id)');
        $this->addSql('ALTER TABLE swot ADD file_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN swot.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE swot ADD CONSTRAINT FK_BC7D6CF693CB796C FOREIGN KEY (file_id) REFERENCES media_object (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BC7D6CF693CB796C ON swot (file_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE swot DROP CONSTRAINT FK_BC7D6CF693CB796C');
        $this->addSql('DROP INDEX UNIQ_BC7D6CF693CB796C');
        $this->addSql('ALTER TABLE swot DROP file_id');
        $this->addSql('ALTER TABLE marketing_mix5 DROP CONSTRAINT FK_9C7CD5E893CB796C');
        $this->addSql('DROP INDEX UNIQ_9C7CD5E893CB796C');
        $this->addSql('ALTER TABLE marketing_mix5 DROP file_id');
        $this->addSql('ALTER TABLE pestel DROP CONSTRAINT FK_9DCE293093CB796C');
        $this->addSql('DROP INDEX UNIQ_9DCE293093CB796C');
        $this->addSql('ALTER TABLE pestel DROP file_id');
        $this->addSql('ALTER TABLE stp DROP CONSTRAINT FK_B2A0C2D593CB796C');
        $this->addSql('DROP INDEX UNIQ_B2A0C2D593CB796C');
        $this->addSql('ALTER TABLE stp DROP file_id');
        $this->addSql('ALTER TABLE marketing_mix4 DROP CONSTRAINT FK_EB7BE57E93CB796C');
        $this->addSql('DROP INDEX UNIQ_EB7BE57E93CB796C');
        $this->addSql('ALTER TABLE marketing_mix4 DROP file_id');
        $this->addSql('ALTER TABLE smart DROP CONSTRAINT FK_B2684D9A93CB796C');
        $this->addSql('DROP INDEX UNIQ_B2684D9A93CB796C');
        $this->addSql('ALTER TABLE smart DROP file_id');
        $this->addSql('ALTER TABLE business_model_canvas DROP CONSTRAINT FK_C550653493CB796C');
        $this->addSql('DROP INDEX UNIQ_C550653493CB796C');
        $this->addSql('ALTER TABLE business_model_canvas DROP file_id');
        $this->addSql('ALTER TABLE golden_triangle DROP CONSTRAINT FK_924B74E393CB796C');
        $this->addSql('DROP INDEX UNIQ_924B74E393CB796C');
        $this->addSql('ALTER TABLE golden_triangle DROP file_id');
        $this->addSql('ALTER TABLE competitor_analysis DROP CONSTRAINT FK_6861F26993CB796C');
        $this->addSql('DROP INDEX UNIQ_6861F26993CB796C');
        $this->addSql('ALTER TABLE competitor_analysis DROP file_id');
        $this->addSql('ALTER TABLE buyer_persona DROP CONSTRAINT FK_F1491FED93CB796C');
        $this->addSql('DROP INDEX UNIQ_F1491FED93CB796C');
        $this->addSql('ALTER TABLE buyer_persona DROP file_id');
    }
}
