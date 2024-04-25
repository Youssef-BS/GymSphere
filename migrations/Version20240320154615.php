<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240320154615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE appartient');
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
        $this->addSql('ALTER TABLE user ADD id_user INT AUTO_INCREMENT NOT NULL, ADD phone_number VARCHAR(255) NOT NULL, ADD is_coach INT NOT NULL, ADD photo_profile VARCHAR(255) NOT NULL, DROP phoneNumber, DROP isAdmin, DROP isCoach, DROP photoProfile, CHANGE otp otp VARCHAR(255) NOT NULL, CHANGE idUser is_admin INT NOT NULL, ADD PRIMARY KEY (id_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (idAbonnement INT AUTO_INCREMENT NOT NULL, dateDebut DATE NOT NULL, dateFin DATE NOT NULL, prix INT NOT NULL, payment TINYINT(1) DEFAULT 0 NOT NULL, idUser INT NOT NULL, PRIMARY KEY(idAbonnement)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE appartient (idUser INT NOT NULL, idGym INT NOT NULL, idAbonnement INT NOT NULL, INDEX idUser (idUser), INDEX idAbonnement (idAbonnement), INDEX idGym (idGym)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
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
        $this->addSql('ALTER TABLE user MODIFY id_user INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON user');
        $this->addSql('ALTER TABLE user ADD idUser INT NOT NULL, ADD phoneNumber VARCHAR(255) NOT NULL, ADD isAdmin TINYINT(1) NOT NULL, ADD isCoach TINYINT(1) NOT NULL, ADD photoProfile VARCHAR(255) NOT NULL, DROP id_user, DROP phone_number, DROP is_admin, DROP is_coach, DROP photo_profile, CHANGE otp otp VARCHAR(4) DEFAULT NULL');
    }
}
