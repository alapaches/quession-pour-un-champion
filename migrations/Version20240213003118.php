<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213003118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE equipe_jeux');
        $this->addSql('DROP TABLE jeux');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe_jeux (equipe_id INTEGER NOT NULL, jeux_id INTEGER NOT NULL, PRIMARY KEY(equipe_id, jeux_id), CONSTRAINT FK_3E1C24F36D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3E1C24F3EC2AA9D2 FOREIGN KEY (jeux_id) REFERENCES jeux (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3E1C24F3EC2AA9D2 ON equipe_jeux (jeux_id)');
        $this->addSql('CREATE INDEX IDX_3E1C24F36D861B89 ON equipe_jeux (equipe_id)');
        $this->addSql('CREATE TABLE jeux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL COLLATE "BINARY")');
    }
}
