<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241228210329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cadeau ADD prix INT DEFAULT NULL');
        $this->addSql('ALTER TABLE liste_cadeau CHANGE utilisateur_id utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE liste_cadeau ADD CONSTRAINT FK_C50415BFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cadeau DROP prix');
        $this->addSql('ALTER TABLE liste_cadeau DROP FOREIGN KEY FK_C50415BFB88E14F');
        $this->addSql('ALTER TABLE liste_cadeau CHANGE utilisateur_id utilisateur_id INT NOT NULL');
    }
}
