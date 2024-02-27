<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227125053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__question AS SELECT id, jeu_id, theme_id, intitule, difficulte FROM question');
        $this->addSql('DROP TABLE question');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, jeu_id INTEGER DEFAULT NULL, theme_id INTEGER DEFAULT NULL, intitule VARCHAR(255) NOT NULL, difficulte VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_B6F7494E8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeux (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B6F7494E59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO question (id, jeu_id, theme_id, intitule, difficulte) SELECT id, jeu_id, theme_id, intitule, difficulte FROM __temp__question');
        $this->addSql('DROP TABLE __temp__question');
        $this->addSql('CREATE INDEX IDX_B6F7494E8C9E392E ON question (jeu_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E59027487 ON question (theme_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__question AS SELECT id, jeu_id, theme_id, intitule, difficulte FROM question');
        $this->addSql('DROP TABLE question');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, jeu_id INTEGER DEFAULT NULL, theme_id INTEGER DEFAULT NULL, intitule VARCHAR(255) NOT NULL, difficulte VARCHAR(255) NOT NULL, CONSTRAINT FK_B6F7494E8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeux (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B6F7494E59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO question (id, jeu_id, theme_id, intitule, difficulte) SELECT id, jeu_id, theme_id, intitule, difficulte FROM __temp__question');
        $this->addSql('DROP TABLE __temp__question');
        $this->addSql('CREATE INDEX IDX_B6F7494E8C9E392E ON question (jeu_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E59027487 ON question (theme_id)');
    }
}
