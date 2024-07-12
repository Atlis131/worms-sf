<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240712094629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE draw ADD include_tools TINYINT(1) DEFAULT 0 NOT NULL, ADD include_sentry_guns TINYINT(1) DEFAULT 0 NOT NULL, ADD include_super_weapons TINYINT(1) DEFAULT 0 NOT NULL, ADD include_open_map_weapons TINYINT(1) DEFAULT 0 NOT NULL, ADD random_weapons_count TINYINT(1) DEFAULT 0 NOT NULL, ADD random_weapons_delay TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE draw_item ADD count SMALLINT DEFAULT NULL, ADD delay SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE draw DROP include_tools, DROP include_sentry_guns, DROP include_super_weapons, DROP include_open_map_weapons, DROP random_weapons_count, DROP random_weapons_delay');
        $this->addSql('ALTER TABLE draw_item DROP count, DROP delay');
    }
}
