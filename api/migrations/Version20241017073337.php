<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017073337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE business_model_canvas (id UUID NOT NULL, project_id UUID NOT NULL, user_id UUID DEFAULT NULL, key_partners TEXT NOT NULL, key_activities TEXT NOT NULL, key_resources TEXT NOT NULL, value_propositions TEXT NOT NULL, customer_relationships TEXT NOT NULL, channels TEXT NOT NULL, customer_segments TEXT NOT NULL, cost_structure TEXT NOT NULL, revenue_streams TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5506534166D1F9C ON business_model_canvas (project_id)');
        $this->addSql('CREATE INDEX IDX_C5506534A76ED395 ON business_model_canvas (user_id)');
        $this->addSql('COMMENT ON COLUMN business_model_canvas.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN business_model_canvas.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN business_model_canvas.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE buyer_persona (id UUID NOT NULL, project_id UUID NOT NULL, user_id UUID DEFAULT NULL, personal_info TEXT NOT NULL, professional_info TEXT NOT NULL, goals_challenges TEXT NOT NULL, communication_channels TEXT NOT NULL, values_fears TEXT NOT NULL, negative_info TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F1491FED166D1F9C ON buyer_persona (project_id)');
        $this->addSql('CREATE INDEX IDX_F1491FEDA76ED395 ON buyer_persona (user_id)');
        $this->addSql('COMMENT ON COLUMN buyer_persona.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN buyer_persona.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN buyer_persona.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE competitor_analysis (id UUID NOT NULL, project_id UUID NOT NULL, user_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6861F269166D1F9C ON competitor_analysis (project_id)');
        $this->addSql('CREATE INDEX IDX_6861F269A76ED395 ON competitor_analysis (user_id)');
        $this->addSql('COMMENT ON COLUMN competitor_analysis.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN competitor_analysis.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN competitor_analysis.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE golden_triangle (id UUID NOT NULL, project_id UUID NOT NULL, user_id UUID DEFAULT NULL, top_label VARCHAR(255) NOT NULL, left_label VARCHAR(255) NOT NULL, right_label VARCHAR(255) NOT NULL, brands JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_924B74E3166D1F9C ON golden_triangle (project_id)');
        $this->addSql('CREATE INDEX IDX_924B74E3A76ED395 ON golden_triangle (user_id)');
        $this->addSql('COMMENT ON COLUMN golden_triangle.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN golden_triangle.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN golden_triangle.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE marketing_mix4 (id UUID NOT NULL, project_id UUID NOT NULL, user_id UUID DEFAULT NULL, product TEXT NOT NULL, price TEXT NOT NULL, place TEXT NOT NULL, promotion TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EB7BE57E166D1F9C ON marketing_mix4 (project_id)');
        $this->addSql('CREATE INDEX IDX_EB7BE57EA76ED395 ON marketing_mix4 (user_id)');
        $this->addSql('COMMENT ON COLUMN marketing_mix4.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN marketing_mix4.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN marketing_mix4.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE marketing_mix5 (id UUID NOT NULL, project_id UUID NOT NULL, user_id UUID DEFAULT NULL, product TEXT NOT NULL, price TEXT NOT NULL, place TEXT NOT NULL, promotion TEXT NOT NULL, people TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9C7CD5E8166D1F9C ON marketing_mix5 (project_id)');
        $this->addSql('CREATE INDEX IDX_9C7CD5E8A76ED395 ON marketing_mix5 (user_id)');
        $this->addSql('COMMENT ON COLUMN marketing_mix5.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN marketing_mix5.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN marketing_mix5.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE media_object (id UUID NOT NULL, user_id UUID DEFAULT NULL, file_path VARCHAR(255) NOT NULL, category VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_14D43132A76ED395 ON media_object (user_id)');
        $this->addSql('COMMENT ON COLUMN media_object.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN media_object.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE pestel (id UUID NOT NULL, project_id UUID NOT NULL, user_id UUID DEFAULT NULL, political TEXT NOT NULL, economic TEXT NOT NULL, social TEXT NOT NULL, technological TEXT NOT NULL, environmental TEXT NOT NULL, legal TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9DCE2930166D1F9C ON pestel (project_id)');
        $this->addSql('CREATE INDEX IDX_9DCE2930A76ED395 ON pestel (user_id)');
        $this->addSql('COMMENT ON COLUMN pestel.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN pestel.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN pestel.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project (id UUID NOT NULL, team_id UUID NOT NULL, picture_id UUID NOT NULL, user_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, status VARCHAR(255) NOT NULL, selling_object VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE296CD8AE ON project (team_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEEE45BDBF ON project (picture_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEA76ED395 ON project (user_id)');
        $this->addSql('COMMENT ON COLUMN project.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.picture_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id SERIAL NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE smart (id UUID NOT NULL, project_id UUID NOT NULL, user_id UUID DEFAULT NULL, key_specific TEXT NOT NULL, key_measurable TEXT NOT NULL, key_achievable TEXT NOT NULL, key_relevant TEXT NOT NULL, key_timed TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B2684D9A166D1F9C ON smart (project_id)');
        $this->addSql('CREATE INDEX IDX_B2684D9AA76ED395 ON smart (user_id)');
        $this->addSql('COMMENT ON COLUMN smart.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN smart.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN smart.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE stp (id UUID NOT NULL, project_id UUID NOT NULL, user_id UUID DEFAULT NULL, segmentation TEXT NOT NULL, targeting TEXT NOT NULL, positioning TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B2A0C2D5166D1F9C ON stp (project_id)');
        $this->addSql('CREATE INDEX IDX_B2A0C2D5A76ED395 ON stp (user_id)');
        $this->addSql('COMMENT ON COLUMN stp.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN stp.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN stp.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE subscription (id UUID NOT NULL, user_id UUID DEFAULT NULL, label VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description TEXT NOT NULL, price INT NOT NULL, recurrence VARCHAR(255) NOT NULL, stripe_product_id VARCHAR(255) DEFAULT NULL, stripe_price_id VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A3C664D3989D9B62 ON subscription (slug)');
        $this->addSql('CREATE INDEX IDX_A3C664D3A76ED395 ON subscription (user_id)');
        $this->addSql('COMMENT ON COLUMN subscription.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN subscription.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE swot (id UUID NOT NULL, project_id UUID NOT NULL, user_id UUID DEFAULT NULL, strengths TEXT NOT NULL, weaknesses TEXT NOT NULL, opportunities TEXT NOT NULL, threats TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC7D6CF6166D1F9C ON swot (project_id)');
        $this->addSql('CREATE INDEX IDX_BC7D6CF6A76ED395 ON swot (user_id)');
        $this->addSql('COMMENT ON COLUMN swot.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN swot.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN swot.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE team (id UUID NOT NULL, manager_id UUID NOT NULL, subscription_id UUID DEFAULT NULL, user_id UUID DEFAULT NULL, stripe_customer_id VARCHAR(255) DEFAULT NULL, stripe_subscription_id VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, subscription_end_at DATE DEFAULT NULL, billing_email VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C4E0A61F783E3463 ON team (manager_id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F9A1887DC ON team (subscription_id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61FA76ED395 ON team (user_id)');
        $this->addSql('COMMENT ON COLUMN team.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team.manager_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team.subscription_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, phone VARCHAR(35) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, last_login_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles JSON NOT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, reset_password_token VARCHAR(255) DEFAULT NULL, reset_password_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649444F97DD ON "user" (phone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".phone IS \'(DC2Type:phone_number)\'');
        $this->addSql('CREATE TABLE user_team (user_id UUID NOT NULL, team_id UUID NOT NULL, PRIMARY KEY(user_id, team_id))');
        $this->addSql('CREATE INDEX IDX_BE61EAD6A76ED395 ON user_team (user_id)');
        $this->addSql('CREATE INDEX IDX_BE61EAD6296CD8AE ON user_team (team_id)');
        $this->addSql('COMMENT ON COLUMN user_team.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_team.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE business_model_canvas ADD CONSTRAINT FK_C5506534166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE business_model_canvas ADD CONSTRAINT FK_C5506534A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE buyer_persona ADD CONSTRAINT FK_F1491FED166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE buyer_persona ADD CONSTRAINT FK_F1491FEDA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE competitor_analysis ADD CONSTRAINT FK_6861F269166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE competitor_analysis ADD CONSTRAINT FK_6861F269A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE golden_triangle ADD CONSTRAINT FK_924B74E3166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE golden_triangle ADD CONSTRAINT FK_924B74E3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE marketing_mix4 ADD CONSTRAINT FK_EB7BE57E166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE marketing_mix4 ADD CONSTRAINT FK_EB7BE57EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE marketing_mix5 ADD CONSTRAINT FK_9C7CD5E8166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE marketing_mix5 ADD CONSTRAINT FK_9C7CD5E8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pestel ADD CONSTRAINT FK_9DCE2930166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pestel ADD CONSTRAINT FK_9DCE2930A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEEE45BDBF FOREIGN KEY (picture_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE smart ADD CONSTRAINT FK_B2684D9A166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE smart ADD CONSTRAINT FK_B2684D9AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stp ADD CONSTRAINT FK_B2A0C2D5166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stp ADD CONSTRAINT FK_B2A0C2D5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE swot ADD CONSTRAINT FK_BC7D6CF6166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE swot ADD CONSTRAINT FK_BC7D6CF6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F783E3463 FOREIGN KEY (manager_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F9A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_team ADD CONSTRAINT FK_BE61EAD6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_team ADD CONSTRAINT FK_BE61EAD6296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE business_model_canvas DROP CONSTRAINT FK_C5506534166D1F9C');
        $this->addSql('ALTER TABLE business_model_canvas DROP CONSTRAINT FK_C5506534A76ED395');
        $this->addSql('ALTER TABLE buyer_persona DROP CONSTRAINT FK_F1491FED166D1F9C');
        $this->addSql('ALTER TABLE buyer_persona DROP CONSTRAINT FK_F1491FEDA76ED395');
        $this->addSql('ALTER TABLE competitor_analysis DROP CONSTRAINT FK_6861F269166D1F9C');
        $this->addSql('ALTER TABLE competitor_analysis DROP CONSTRAINT FK_6861F269A76ED395');
        $this->addSql('ALTER TABLE golden_triangle DROP CONSTRAINT FK_924B74E3166D1F9C');
        $this->addSql('ALTER TABLE golden_triangle DROP CONSTRAINT FK_924B74E3A76ED395');
        $this->addSql('ALTER TABLE marketing_mix4 DROP CONSTRAINT FK_EB7BE57E166D1F9C');
        $this->addSql('ALTER TABLE marketing_mix4 DROP CONSTRAINT FK_EB7BE57EA76ED395');
        $this->addSql('ALTER TABLE marketing_mix5 DROP CONSTRAINT FK_9C7CD5E8166D1F9C');
        $this->addSql('ALTER TABLE marketing_mix5 DROP CONSTRAINT FK_9C7CD5E8A76ED395');
        $this->addSql('ALTER TABLE media_object DROP CONSTRAINT FK_14D43132A76ED395');
        $this->addSql('ALTER TABLE pestel DROP CONSTRAINT FK_9DCE2930166D1F9C');
        $this->addSql('ALTER TABLE pestel DROP CONSTRAINT FK_9DCE2930A76ED395');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE296CD8AE');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEEE45BDBF');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEA76ED395');
        $this->addSql('ALTER TABLE smart DROP CONSTRAINT FK_B2684D9A166D1F9C');
        $this->addSql('ALTER TABLE smart DROP CONSTRAINT FK_B2684D9AA76ED395');
        $this->addSql('ALTER TABLE stp DROP CONSTRAINT FK_B2A0C2D5166D1F9C');
        $this->addSql('ALTER TABLE stp DROP CONSTRAINT FK_B2A0C2D5A76ED395');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D3A76ED395');
        $this->addSql('ALTER TABLE swot DROP CONSTRAINT FK_BC7D6CF6166D1F9C');
        $this->addSql('ALTER TABLE swot DROP CONSTRAINT FK_BC7D6CF6A76ED395');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F783E3463');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F9A1887DC');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61FA76ED395');
        $this->addSql('ALTER TABLE user_team DROP CONSTRAINT FK_BE61EAD6A76ED395');
        $this->addSql('ALTER TABLE user_team DROP CONSTRAINT FK_BE61EAD6296CD8AE');
        $this->addSql('DROP TABLE business_model_canvas');
        $this->addSql('DROP TABLE buyer_persona');
        $this->addSql('DROP TABLE competitor_analysis');
        $this->addSql('DROP TABLE golden_triangle');
        $this->addSql('DROP TABLE marketing_mix4');
        $this->addSql('DROP TABLE marketing_mix5');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP TABLE pestel');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE smart');
        $this->addSql('DROP TABLE stp');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE swot');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_team');
    }
}
