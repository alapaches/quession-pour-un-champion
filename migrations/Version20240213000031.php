<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213000031 extends AbstractMigration
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
        $this->addSql('CREATE TABLE jeux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, equipe_gagnante_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL COLLATE "BINARY", points DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_3755B50D9D2B864A FOREIGN KEY (equipe_gagnante_id) REFERENCES equipe (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3755B50D9D2B864A ON jeux (equipe_gagnante_id)');
    }
}
