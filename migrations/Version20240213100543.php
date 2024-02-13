<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213100543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jeux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE score (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, jeu_id INTEGER DEFAULT NULL, total DOUBLE PRECISION NOT NULL, CONSTRAINT FK_329937518C9E392E FOREIGN KEY (jeu_id) REFERENCES jeux (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_329937518C9E392E ON score (jeu_id)');
        $this->addSql('CREATE TABLE score_equipe (score_id INTEGER NOT NULL, equipe_id INTEGER NOT NULL, PRIMARY KEY(score_id, equipe_id), CONSTRAINT FK_C793FDCB12EB0A51 FOREIGN KEY (score_id) REFERENCES score (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C793FDCB6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C793FDCB12EB0A51 ON score_equipe (score_id)');
        $this->addSql('CREATE INDEX IDX_C793FDCB6D861B89 ON score_equipe (equipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE jeux');
        $this->addSql('DROP TABLE score');
        $this->addSql('DROP TABLE score_equipe');
    }
}
