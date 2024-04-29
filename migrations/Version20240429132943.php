<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429132943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE appartient');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE calandrier');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE gym');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE program');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE repondre');
        $this->addSql('DROP TABLE userprogram');
        $this->addSql('ALTER TABLE abonnement ADD date_debut DATE NOT NULL, ADD date_fin DATE NOT NULL, DROP dateDebut, DROP dateFin, CHANGE payment payment TINYINT(1) NOT NULL, CHANGE idUser id_user INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE isAdmin isAdmin INT NOT NULL, CHANGE isCoach isCoach INT NOT NULL, CHANGE photoProfile photoProfile VARCHAR(255) NOT NULL, CHANGE otp otp VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appartient (idUser INT NOT NULL, idGym INT NOT NULL, idAbonnement INT NOT NULL, INDEX idAbonnement (idAbonnement), INDEX idGym (idGym), INDEX idUser (idUser)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE avis (idAvis INT AUTO_INCREMENT NOT NULL, avis INT NOT NULL, idUser INT NOT NULL, INDEX idUser (idUser), PRIMARY KEY(idAvis)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE calandrier (idCalandrier INT AUTO_INCREMENT NOT NULL, date_activite DATE NOT NULL, typeActivite VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, description VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, heureFermuture DATE NOT NULL, idGym INT NOT NULL, INDEX idGym (idGym), PRIMARY KEY(idCalandrier)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande (idCommande INT AUTO_INCREMENT NOT NULL, idUser INT NOT NULL, Total INT NOT NULL, commandeSt INT DEFAULT 0 NOT NULL, INDEX idUser (idUser), PRIMARY KEY(idCommande)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, duree VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_debut DATE NOT NULL, nb_participants INT DEFAULT NULL, nb_max INT NOT NULL, localisation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, idProgramme INT DEFAULT NULL, INDEX idProgramme (idProgramme), PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, duree VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, difficulte VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, video VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, idprogramme INT NOT NULL, INDEX idProgramme (idprogramme), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE gym (idGym INT AUTO_INCREMENT NOT NULL, nomGym VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, adresse VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, photoGym VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, PRIMARY KEY(idGym)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE panier (idPanier INT AUTO_INCREMENT NOT NULL, idUser INT NOT NULL, idProduit INT NOT NULL, status INT DEFAULT 0 NOT NULL, PRIMARY KEY(idPanier)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produit (idProduit INT AUTO_INCREMENT NOT NULL, nomProduit VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, prixProduit INT NOT NULL, photoProduit VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, quantiteProduit INT NOT NULL, PRIMARY KEY(idProduit)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE program (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(500) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, duree VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, registration_deadline DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, imgsrc VARCHAR(500) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, idUser INT DEFAULT NULL, INDEX idUser (idUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, type VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, description VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, date_reclamation DATE NOT NULL, iduser INT DEFAULT NULL, INDEX idUser (iduser), PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE repondre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, reponse VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, date_reponse DATE NOT NULL, iduser INT NOT NULL, idreclamation INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE userprogram (id INT AUTO_INCREMENT NOT NULL, userId INT NOT NULL, programId INT NOT NULL, INDEX fk_userprogram_program (programId), INDEX fk_userprogram_user (userId), PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE abonnement ADD dateDebut DATE NOT NULL, ADD dateFin DATE NOT NULL, DROP date_debut, DROP date_fin, CHANGE payment payment TINYINT(1) DEFAULT 0 NOT NULL, CHANGE id_user idUser INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE isAdmin isAdmin TINYINT(1) DEFAULT 0, CHANGE isCoach isCoach TINYINT(1) DEFAULT 0, CHANGE photoProfile photoProfile VARCHAR(255) DEFAULT NULL, CHANGE otp otp VARCHAR(4) DEFAULT NULL');
    }
}
