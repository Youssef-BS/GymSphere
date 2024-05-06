<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425140718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement CHANGE payment payment TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX idGym_2 ON calandrier');
        $this->addSql('ALTER TABLE calandrier CHANGE idGym idGym INT NOT NULL');
        $this->addSql('ALTER TABLE gym CHANGE photoGym photoGym VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE isAdmin isAdmin TINYINT(1) DEFAULT NULL, CHANGE isCoach isCoach TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE abonnement CHANGE payment payment TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE calandrier DROP FOREIGN KEY FK_463A18AA5E6FC66A');
        $this->addSql('ALTER TABLE calandrier CHANGE idGym idGym INT DEFAULT NULL');
        $this->addSql('CREATE INDEX idGym_2 ON calandrier (idGym)');
        $this->addSql('ALTER TABLE gym CHANGE photoGym photoGym VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE isAdmin isAdmin TINYINT(1) DEFAULT 0, CHANGE isCoach isCoach TINYINT(1) DEFAULT 0');
    }
}
