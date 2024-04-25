<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240420222823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement CHANGE payment payment TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE commande CHANGE commandeSt commandeSt INT NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(500) NOT NULL, CHANGE duree duree VARCHAR(255) NOT NULL, CHANGE type type VARCHAR(255) NOT NULL, CHANGE localisation localisation VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE panier CHANGE status status INT NOT NULL');
        $this->addSql('ALTER TABLE program CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('CREATE INDEX IDX_F66FAAF48A114AB ON repondre (idreclamation)');
        $this->addSql('ALTER TABLE user CHANGE isAdmin isAdmin TINYINT(1) DEFAULT NULL, CHANGE isCoach isCoach TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement CHANGE payment payment TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE commande CHANGE commandeSt commandeSt INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE duree duree VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE localisation localisation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE status status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE panier CHANGE status status INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE program CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE repondre DROP FOREIGN KEY FK_F66FAAF48A114AB');
        $this->addSql('DROP INDEX IDX_F66FAAF48A114AB ON repondre');
        $this->addSql('ALTER TABLE user CHANGE isAdmin isAdmin TINYINT(1) DEFAULT 0, CHANGE isCoach isCoach TINYINT(1) DEFAULT 0');
    }
}
