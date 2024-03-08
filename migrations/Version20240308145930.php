<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308145930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__son AS SELECT id, categorie_id, nom, points FROM son');
        $this->addSql('DROP TABLE son');
        $this->addSql('CREATE TABLE son (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER NOT NULL, proposition_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, points INTEGER NOT NULL, CONSTRAINT FK_E199342CBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E199342CDB96F9E FOREIGN KEY (proposition_id) REFERENCES proposition (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO son (id, categorie_id, nom, points) SELECT id, categorie_id, nom, points FROM __temp__son');
        $this->addSql('DROP TABLE __temp__son');
        $this->addSql('CREATE INDEX IDX_E199342CBCF5E72D ON son (categorie_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E199342CDB96F9E ON son (proposition_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__son AS SELECT id, categorie_id, nom, points FROM son');
        $this->addSql('DROP TABLE son');
        $this->addSql('CREATE TABLE son (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, points INTEGER NOT NULL, CONSTRAINT FK_E199342CBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO son (id, categorie_id, nom, points) SELECT id, categorie_id, nom, points FROM __temp__son');
        $this->addSql('DROP TABLE __temp__son');
        $this->addSql('CREATE INDEX IDX_E199342CBCF5E72D ON son (categorie_id)');
    }
}
