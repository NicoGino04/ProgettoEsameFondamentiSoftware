<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260103233837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__dato AS SELECT id, tipo, data, quantità, user_id FROM dato');
        $this->addSql('DROP TABLE dato');
        $this->addSql('CREATE TABLE dato (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tipo VARCHAR(255) NOT NULL, data DATE NOT NULL, quantita INTEGER NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_4A4BDE64A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO dato (id, tipo, data, quantita, user_id) SELECT id, tipo, data, quantità, user_id FROM __temp__dato');
        $this->addSql('DROP TABLE __temp__dato');
        $this->addSql('CREATE INDEX IDX_4A4BDE64A76ED395 ON dato (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__dato AS SELECT id, tipo, data, quantita, user_id FROM dato');
        $this->addSql('DROP TABLE dato');
        $this->addSql('CREATE TABLE dato (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tipo VARCHAR(255) NOT NULL, data DATE NOT NULL, quantità INTEGER NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_4A4BDE64A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO dato (id, tipo, data, quantità, user_id) SELECT id, tipo, data, quantita, user_id FROM __temp__dato');
        $this->addSql('DROP TABLE __temp__dato');
        $this->addSql('CREATE INDEX IDX_4A4BDE64A76ED395 ON dato (user_id)');
    }
}
