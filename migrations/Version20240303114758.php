<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303114758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE draw (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_70F2BD0FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE draw_item (id INT AUTO_INCREMENT NOT NULL, draw_id INT NOT NULL, weapon_id INT NOT NULL, INDEX IDX_B6098ADB6FC5C1B8 (draw_id), INDEX IDX_B6098ADB95B82273 (weapon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE draw ADD CONSTRAINT FK_70F2BD0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE draw_item ADD CONSTRAINT FK_B6098ADB6FC5C1B8 FOREIGN KEY (draw_id) REFERENCES draw (id)');
        $this->addSql('ALTER TABLE draw_item ADD CONSTRAINT FK_B6098ADB95B82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE draw DROP FOREIGN KEY FK_70F2BD0FA76ED395');
        $this->addSql('ALTER TABLE draw_item DROP FOREIGN KEY FK_B6098ADB6FC5C1B8');
        $this->addSql('ALTER TABLE draw_item DROP FOREIGN KEY FK_B6098ADB95B82273');
        $this->addSql('DROP TABLE draw');
        $this->addSql('DROP TABLE draw_item');
    }
}
