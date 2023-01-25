<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509184345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (idEvent INT AUTO_INCREMENT NOT NULL, nomEvent VARCHAR(255) NOT NULL, prixEvent DOUBLE PRECISION NOT NULL, date DATE NOT NULL, UNIQUE INDEX idEvent (idEvent), PRIMARY KEY(idEvent)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maisonh (id_maison INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, localisation VARCHAR(1000) NOT NULL, description VARCHAR(1000) NOT NULL, prix DOUBLE PRECISION NOT NULL, image_maison VARCHAR(100) NOT NULL, PRIMARY KEY(id_maison)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication (idPub INT AUTO_INCREMENT NOT NULL, datePub DATE NOT NULL, Path VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(idPub)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (numero INT AUTO_INCREMENT NOT NULL, id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, commentaire VARCHAR(255) NOT NULL, dateReclamation DATE NOT NULL, typeReclamation VARCHAR(255) NOT NULL, INDEX id (id), PRIMARY KEY(numero)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponsereclamation (numero INT DEFAULT NULL, IdRep INT AUTO_INCREMENT NOT NULL, Reponse VARCHAR(255) NOT NULL, INDEX numero (numero), PRIMARY KEY(IdRep)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (idClient INT AUTO_INCREMENT NOT NULL, idEvent INT NOT NULL, nomClient VARCHAR(255) NOT NULL, prenomClient VARCHAR(255) NOT NULL, PRIMARY KEY(idClient)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservationevenement (idRE INT AUTO_INCREMENT NOT NULL, nomRE VARCHAR(255) NOT NULL, dateRE DATE NOT NULL, idEvent INT DEFAULT NULL, INDEX idEvent (idEvent), PRIMARY KEY(idRE)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservationm (id_res INT AUTO_INCREMENT NOT NULL, id_maison INT DEFAULT NULL, nb_chambre INT NOT NULL, nb_personne INT NOT NULL, date VARCHAR(50) NOT NULL, INDEX maisonH_reservationm (id_maison), PRIMARY KEY(id_res)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservationv (IdR INT AUTO_INCREMENT NOT NULL, NB_personnes INT NOT NULL, Date VARCHAR(30) NOT NULL, Id INT DEFAULT NULL, INDEX Id (Id), PRIMARY KEY(IdR)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor (idS INT AUTO_INCREMENT NOT NULL, prenomS VARCHAR(255) NOT NULL, descriptionS VARCHAR(255) NOT NULL, nomS VARCHAR(255) NOT NULL, imageS VARCHAR(255) NOT NULL, UNIQUE INDEX idS (idS), PRIMARY KEY(idS)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voyage (Id INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(150) NOT NULL, Destination VARCHAR(30) NOT NULL, Description VARCHAR(2000) NOT NULL, Prix INT NOT NULL, Image VARCHAR(150) NOT NULL, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404BF396750 FOREIGN KEY (id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reponsereclamation ADD CONSTRAINT FK_B052BA70F55AE19E FOREIGN KEY (numero) REFERENCES reclamation (numero)');
        $this->addSql('ALTER TABLE reservationevenement ADD CONSTRAINT FK_B853CF6A2C6A49BA FOREIGN KEY (idEvent) REFERENCES evenement (idEvent)');
        $this->addSql('ALTER TABLE reservationm ADD CONSTRAINT FK_FA429F5ADF3B0380 FOREIGN KEY (id_maison) REFERENCES maisonh (id_maison)');
        $this->addSql('ALTER TABLE reservationv ADD CONSTRAINT FK_702756B62ABD43F2 FOREIGN KEY (Id) REFERENCES voyage (Id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservationevenement DROP FOREIGN KEY FK_B853CF6A2C6A49BA');
        $this->addSql('ALTER TABLE reservationm DROP FOREIGN KEY FK_FA429F5ADF3B0380');
        $this->addSql('ALTER TABLE reponsereclamation DROP FOREIGN KEY FK_B052BA70F55AE19E');
        $this->addSql('ALTER TABLE reservationv DROP FOREIGN KEY FK_702756B62ABD43F2');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE maisonh');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponsereclamation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservationevenement');
        $this->addSql('DROP TABLE reservationm');
        $this->addSql('DROP TABLE reservationv');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP TABLE voyage');
    }
}
