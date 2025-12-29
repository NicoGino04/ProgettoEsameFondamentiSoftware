<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251229214557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pasto AS SELECT id, pasto, tipo, giorno, user_id FROM pasto');
        $this->addSql('DROP TABLE pasto');
        $this->addSql('CREATE TABLE pasto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pasto VARCHAR(255) NOT NULL, tipo VARCHAR(255) NOT NULL, giorno DATE NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_7C839186A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pasto (id, pasto, tipo, giorno, user_id) SELECT id, pasto, tipo, giorno, user_id FROM __temp__pasto');
        $this->addSql('DROP TABLE __temp__pasto');
        $this->addSql('CREATE INDEX IDX_7C839186A76ED395 ON pasto (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pasto ADD COLUMN carboidrati INTEGER NOT NULL');
        $this->addSql('ALTER TABLE pasto ADD COLUMN grassi INTEGER NOT NULL');
        $this->addSql('ALTER TABLE pasto ADD COLUMN proteine VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE pasto ADD COLUMN calorie INTEGER NOT NULL');
    }
}
