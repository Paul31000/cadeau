<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241221175206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cadeau ADD titre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE groupe_cadeau ADD nom_groupe VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE liste_cadeau ADD titre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD entitee_liee VARCHAR(255) DEFAULT NULL, ADD id_entitee_liee INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user ADD pseudo VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_PSEUDO ON user (pseudo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cadeau DROP titre');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_PSEUDO ON `user`');
        $this->addSql('ALTER TABLE `user` DROP pseudo');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON `user` (email)');
        $this->addSql('ALTER TABLE message DROP entitee_liee, DROP id_entitee_liee');
        $this->addSql('ALTER TABLE liste_cadeau DROP titre');
        $this->addSql('ALTER TABLE groupe_cadeau DROP nom_groupe');
    }
}
