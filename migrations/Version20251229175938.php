<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251229175938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pasto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pasto VARCHAR(255) NOT NULL, tipo VARCHAR(255) NOT NULL, carboidrati INTEGER NOT NULL, grassi INTEGER NOT NULL, proteine VARCHAR(255) NOT NULL, calorie INTEGER NOT NULL, giorno DATE NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_7C839186A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7C839186A76ED395 ON pasto (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pasto');
    }
}
