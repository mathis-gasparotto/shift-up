<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110163108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE buyer_persona ALTER personal_info TYPE JSON USING personal_info::json");
        $this->addSql("ALTER TABLE buyer_persona ALTER professional_info TYPE JSON USING professional_info::json");
        $this->addSql("ALTER TABLE buyer_persona ALTER communication_channels TYPE JSON USING communication_channels::json");
        $this->addSql("ALTER TABLE buyer_persona ALTER values_fears TYPE JSON USING values_fears::json");
        $this->addSql("ALTER TABLE buyer_persona ALTER negative_info TYPE JSON USING negative_info::json");
        $this->addSql("ALTER TABLE swot ALTER strengths TYPE JSON USING strengths::json");
        $this->addSql("ALTER TABLE swot ALTER weaknesses TYPE JSON USING weaknesses::json");
        $this->addSql("ALTER TABLE swot ALTER opportunities TYPE JSON USING opportunities::json");
        $this->addSql("ALTER TABLE swot ALTER threats TYPE JSON USING threats::json");
        $this->addSql('ALTER TABLE business_model_canvas ALTER key_partners TYPE JSON USING key_partners::json');
        $this->addSql('ALTER TABLE business_model_canvas ALTER key_activities TYPE JSON USING key_activities::json');
        $this->addSql('ALTER TABLE business_model_canvas ALTER key_resources TYPE JSON USING key_resources::json');
        $this->addSql('ALTER TABLE business_model_canvas ALTER value_propositions TYPE JSON USING value_propositions::json');
        $this->addSql('ALTER TABLE business_model_canvas ALTER customer_relationships TYPE JSON USING customer_relationships::json');
        $this->addSql('ALTER TABLE business_model_canvas ALTER channels TYPE JSON USING channels::json');
        $this->addSql('ALTER TABLE business_model_canvas ALTER customer_segments TYPE JSON USING customer_segments::json');
        $this->addSql('ALTER TABLE business_model_canvas ALTER cost_structure TYPE JSON USING cost_structure::json');
        $this->addSql('ALTER TABLE business_model_canvas ALTER revenue_streams TYPE JSON USING revenue_streams::json');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE buyer_persona ALTER personal_info TYPE TEXT');
        $this->addSql('ALTER TABLE buyer_persona ALTER professional_info TYPE TEXT');
        $this->addSql('ALTER TABLE buyer_persona ALTER communication_channels TYPE TEXT');
        $this->addSql('ALTER TABLE buyer_persona ALTER values_fears TYPE TEXT');
        $this->addSql('ALTER TABLE buyer_persona ALTER negative_info TYPE TEXT');
        $this->addSql('ALTER TABLE swot ALTER strengths TYPE TEXT');
        $this->addSql('ALTER TABLE swot ALTER weaknesses TYPE TEXT');
        $this->addSql('ALTER TABLE swot ALTER opportunities TYPE TEXT');
        $this->addSql('ALTER TABLE swot ALTER threats TYPE TEXT');
        $this->addSql('ALTER TABLE business_model_canvas ALTER key_partners TYPE TEXT');
        $this->addSql('ALTER TABLE business_model_canvas ALTER key_activities TYPE TEXT');
        $this->addSql('ALTER TABLE business_model_canvas ALTER key_resources TYPE TEXT');
        $this->addSql('ALTER TABLE business_model_canvas ALTER value_propositions TYPE TEXT');
        $this->addSql('ALTER TABLE business_model_canvas ALTER customer_relationships TYPE TEXT');
        $this->addSql('ALTER TABLE business_model_canvas ALTER channels TYPE TEXT');
        $this->addSql('ALTER TABLE business_model_canvas ALTER customer_segments TYPE TEXT');
        $this->addSql('ALTER TABLE business_model_canvas ALTER cost_structure TYPE TEXT');
        $this->addSql('ALTER TABLE business_model_canvas ALTER revenue_streams TYPE TEXT');
    }
}
