<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260110092749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD COLUMN username VARCHAR(255) DEFAULT "" NOT NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN avatar VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, età, peso, altezza, basale, sesso, misura_peso, misura_altezza FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, età INTEGER DEFAULT NULL, peso DOUBLE PRECISION DEFAULT NULL, altezza DOUBLE PRECISION DEFAULT NULL, basale DOUBLE PRECISION DEFAULT NULL, sesso VARCHAR(255) DEFAULT NULL, misura_peso VARCHAR(255) DEFAULT NULL, misura_altezza VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, età, peso, altezza, basale, sesso, misura_peso, misura_altezza) SELECT id, email, roles, password, età, peso, altezza, basale, sesso, misura_peso, misura_altezza FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }
}
