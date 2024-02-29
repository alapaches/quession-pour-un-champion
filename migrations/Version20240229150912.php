<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229150912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE jeux (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE participant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, equipe_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, CONSTRAINT FK_D79F6B116D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D79F6B116D861B89 ON participant (equipe_id)');
        $this->addSql('CREATE TABLE proposition (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, question_id INTEGER NOT NULL, titre VARCHAR(255) NOT NULL, valide BOOLEAN NOT NULL, CONSTRAINT FK_C7CDC3531E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C7CDC3531E27F6BF ON proposition (question_id)');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, jeu_id INTEGER DEFAULT NULL, theme_id INTEGER DEFAULT NULL, intitule VARCHAR(255) NOT NULL, difficulte VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_B6F7494E8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeux (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B6F7494E59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B6F7494E8C9E392E ON question (jeu_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E59027487 ON question (theme_id)');
        $this->addSql('CREATE TABLE score_equipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, jeu_id INTEGER NOT NULL, equipe_id INTEGER NOT NULL, score DOUBLE PRECISION NOT NULL, CONSTRAINT FK_C793FDCB8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeux (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C793FDCB6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C793FDCB8C9E392E ON score_equipe (jeu_id)');
        $this->addSql('CREATE INDEX IDX_C793FDCB6D861B89 ON score_equipe (equipe_id)');
        $this->addSql('CREATE TABLE theme (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE jeux');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE proposition');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE score_equipe');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
