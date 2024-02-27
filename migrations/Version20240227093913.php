<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227093913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weapon CHANGE min min SMALLINT DEFAULT 1 NOT NULL, CHANGE max max SMALLINT DEFAULT 10 NOT NULL');
        $this->addSql('UPDATE weapon SET min = 1, max = 10 WHERE 1;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weapon CHANGE min min SMALLINT DEFAULT NULL, CHANGE max max SMALLINT DEFAULT NULL');
        $this->addSql('UPDATE weapon SET min = null, max = null WHERE 1;');
    }
}
