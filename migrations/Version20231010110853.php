<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010110853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tricks_group ADD CONSTRAINT FK_B4F07AAC3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tricks_group ADD CONSTRAINT FK_B4F07AACFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tricks_image ADD CONSTRAINT FK_1C0D3A363B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE tricks_video ADD CONSTRAINT FK_A5F7E4453B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL, ADD image_name VARCHAR(255) DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C1A76ED395');
        $this->addSql('ALTER TABLE tricks_group DROP FOREIGN KEY FK_B4F07AAC3B153154');
        $this->addSql('ALTER TABLE tricks_group DROP FOREIGN KEY FK_B4F07AACFE54D947');
        $this->addSql('ALTER TABLE tricks_image DROP FOREIGN KEY FK_1C0D3A363B153154');
        $this->addSql('ALTER TABLE tricks_video DROP FOREIGN KEY FK_A5F7E4453B153154');
        $this->addSql('ALTER TABLE user DROP reset_token, DROP image_name, DROP updated_at');
    }
}
