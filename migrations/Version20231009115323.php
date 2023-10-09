<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231009115323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tricks_group DROP FOREIGN KEY FK_B4F07AAC3B153154');
        $this->addSql('ALTER TABLE tricks_group ADD CONSTRAINT FK_B4F07AAC3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tricks_image DROP id_tricks');
        $this->addSql('ALTER TABLE tricks_video DROP id_tricks');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tricks_group DROP FOREIGN KEY FK_B4F07AAC3B153154');
        $this->addSql('ALTER TABLE tricks_group ADD CONSTRAINT FK_B4F07AAC3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE tricks_image ADD id_tricks INT NOT NULL');
        $this->addSql('ALTER TABLE tricks_video ADD id_tricks INT NOT NULL');
    }
}
