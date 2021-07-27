<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200703101755 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campaign DROP title, DROP content, DROP created_at, DROP updated_at, DROP goal, DROP name, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY fk_participant_campaign1_idx');
        $this->addSql('DROP INDEX fk_participant_campaign1_idx ON participant');
        $this->addSql('ALTER TABLE participant DROP campaign_id, DROP name, DROP email');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY fk_payment_participant1');
        $this->addSql('DROP INDEX fk_payment_participant1_idx ON payment');
        $this->addSql('ALTER TABLE payment DROP participant_id, DROP amount, DROP created_at, DROP updated_at');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campaign ADD title VARCHAR(150) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, ADD content TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, ADD created_at DATETIME DEFAULT \'NULL\', ADD updated_at DATETIME DEFAULT \'NULL\', ADD goal INT DEFAULT NULL, ADD name VARCHAR(150) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE id id VARCHAR(32) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE participant ADD campaign_id VARCHAR(32) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, ADD name VARCHAR(200) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, ADD email VARCHAR(200) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fk_participant_campaign1_idx FOREIGN KEY (campaign_id) REFERENCES campaign (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX fk_participant_campaign1_idx ON participant (campaign_id)');
        $this->addSql('ALTER TABLE payment ADD participant_id INT NOT NULL, ADD amount INT DEFAULT NULL, ADD created_at DATETIME DEFAULT \'NULL\', ADD updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT fk_payment_participant1 FOREIGN KEY (participant_id) REFERENCES participant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX fk_payment_participant1_idx ON payment (participant_id)');
    }
}
