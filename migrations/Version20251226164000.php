<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251226164000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__goal AS SELECT id, name, quantity, expiration, goal_quantity, user_id FROM goal');
        $this->addSql('DROP TABLE goal');
        $this->addSql('CREATE TABLE goal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, quantity BIGINT DEFAULT 0 NOT NULL, expiration DATE DEFAULT NULL, goal_quantity BIGINT NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_FCDCEB2EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO goal (id, name, quantity, expiration, goal_quantity, user_id) SELECT id, name, quantity, expiration, goal_quantity, user_id FROM __temp__goal');
        $this->addSql('DROP TABLE __temp__goal');
        $this->addSql('CREATE INDEX IDX_FCDCEB2EA76ED395 ON goal (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__goal AS SELECT id, name, quantity, expiration, goal_quantity, user_id FROM goal');
        $this->addSql('DROP TABLE goal');
        $this->addSql('CREATE TABLE goal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, quantity BIGINT NOT NULL, expiration DATE DEFAULT NULL, goal_quantity BIGINT NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_FCDCEB2EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO goal (id, name, quantity, expiration, goal_quantity, user_id) SELECT id, name, quantity, expiration, goal_quantity, user_id FROM __temp__goal');
        $this->addSql('DROP TABLE __temp__goal');
        $this->addSql('CREATE INDEX IDX_FCDCEB2EA76ED395 ON goal (user_id)');
    }
}
