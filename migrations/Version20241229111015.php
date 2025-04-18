<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229111015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste_cadeau ADD user_a_id INT DEFAULT NULL, ADD groupe_cadeau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE liste_cadeau ADD CONSTRAINT FK_C50415B415F1F91 FOREIGN KEY (user_a_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE liste_cadeau ADD CONSTRAINT FK_C50415B89413EB3 FOREIGN KEY (groupe_cadeau_id) REFERENCES groupe_cadeau (id)');
        $this->addSql('CREATE INDEX IDX_C50415B415F1F91 ON liste_cadeau (user_a_id)');
        $this->addSql('CREATE INDEX IDX_C50415B89413EB3 ON liste_cadeau (groupe_cadeau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste_cadeau DROP FOREIGN KEY FK_C50415B415F1F91');
        $this->addSql('ALTER TABLE liste_cadeau DROP FOREIGN KEY FK_C50415B89413EB3');
        $this->addSql('DROP INDEX IDX_C50415B415F1F91 ON liste_cadeau');
        $this->addSql('DROP INDEX IDX_C50415B89413EB3 ON liste_cadeau');
        $this->addSql('ALTER TABLE liste_cadeau DROP user_a_id, DROP groupe_cadeau_id');
    }
}
