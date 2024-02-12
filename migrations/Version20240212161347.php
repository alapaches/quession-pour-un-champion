<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212161347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE jeux');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jeux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, gagnants_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL COLLATE "BINARY", points DOUBLE PRECISION NOT NULL, CONSTRAINT FK_3755B50D22C97FD9 FOREIGN KEY (gagnants_id) REFERENCES equipe (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3755B50D22C97FD9 ON jeux (gagnants_id)');
    }
}
