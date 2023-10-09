<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231009114955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tricks (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(50) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E1D902C1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tricks_group (tricks_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_B4F07AAC3B153154 (tricks_id), INDEX IDX_B4F07AACFE54D947 (group_id), PRIMARY KEY(tricks_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tricks_image (id INT AUTO_INCREMENT NOT NULL, tricks_id INT NOT NULL, id_tricks INT NOT NULL, name VARCHAR(150) NOT NULL, INDEX IDX_1C0D3A363B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tricks_video (id INT AUTO_INCREMENT NOT NULL, tricks_id INT DEFAULT NULL, id_tricks INT NOT NULL, name VARCHAR(150) NOT NULL, INDEX IDX_A5F7E4453B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tricks_group ADD CONSTRAINT FK_B4F07AAC3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE tricks_group ADD CONSTRAINT FK_B4F07AACFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tricks_image ADD CONSTRAINT FK_1C0D3A363B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE tricks_video ADD CONSTRAINT FK_A5F7E4453B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C1A76ED395');
        $this->addSql('ALTER TABLE tricks_group DROP FOREIGN KEY FK_B4F07AAC3B153154');
        $this->addSql('ALTER TABLE tricks_group DROP FOREIGN KEY FK_B4F07AACFE54D947');
        $this->addSql('ALTER TABLE tricks_image DROP FOREIGN KEY FK_1C0D3A363B153154');
        $this->addSql('ALTER TABLE tricks_video DROP FOREIGN KEY FK_A5F7E4453B153154');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE tricks');
        $this->addSql('DROP TABLE tricks_group');
        $this->addSql('DROP TABLE tricks_image');
        $this->addSql('DROP TABLE tricks_video');
    }
}
