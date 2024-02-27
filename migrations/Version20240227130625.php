<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227130625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weapon_log ADD weapon_id INT NOT NULL');
        $this->addSql('ALTER TABLE weapon_log ADD CONSTRAINT FK_2D7298F295B82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id)');
        $this->addSql('CREATE INDEX IDX_2D7298F295B82273 ON weapon_log (weapon_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weapon_log DROP FOREIGN KEY FK_2D7298F295B82273');
        $this->addSql('DROP INDEX IDX_2D7298F295B82273 ON weapon_log');
        $this->addSql('ALTER TABLE weapon_log DROP weapon_id');
    }
}
