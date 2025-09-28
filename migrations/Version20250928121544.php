<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250928121544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', expires_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jointable_user_user_ami (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_1F33E9263AD8644E (user_source), INDEX IDX_1F33E926233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jointable_user_user_ami_demande (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_9BA3CB9D3AD8644E (user_source), INDEX IDX_9BA3CB9D233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE jointable_user_user_ami ADD CONSTRAINT FK_1F33E9263AD8644E FOREIGN KEY (user_source) REFERENCES `user` (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE jointable_user_user_ami ADD CONSTRAINT FK_1F33E926233D34C1 FOREIGN KEY (user_target) REFERENCES `user` (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE jointable_user_user_ami_demande ADD CONSTRAINT FK_9BA3CB9D3AD8644E FOREIGN KEY (user_source) REFERENCES `user` (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE jointable_user_user_ami_demande ADD CONSTRAINT FK_9BA3CB9D233D34C1 FOREIGN KEY (user_target) REFERENCES `user` (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD adresse VARCHAR(500) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE jointable_user_user_ami DROP FOREIGN KEY FK_1F33E9263AD8644E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE jointable_user_user_ami DROP FOREIGN KEY FK_1F33E926233D34C1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE jointable_user_user_ami_demande DROP FOREIGN KEY FK_9BA3CB9D3AD8644E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE jointable_user_user_ami_demande DROP FOREIGN KEY FK_9BA3CB9D233D34C1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reset_password_request
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jointable_user_user_ami
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jointable_user_user_ami_demande
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` DROP adresse
        SQL);
    }
}
