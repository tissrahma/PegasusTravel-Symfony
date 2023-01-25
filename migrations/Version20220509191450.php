<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509191450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX id ON reclamation');
        $this->addSql('ALTER TABLE reclamation CHANGE id_user id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404BF396750 FOREIGN KEY (id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX id ON reclamation (id)');
        $this->addSql('ALTER TABLE reponsereclamation ADD CONSTRAINT FK_B052BA70F55AE19E FOREIGN KEY (numero) REFERENCES reclamation (numero)');
        $this->addSql('ALTER TABLE reservationevenement ADD CONSTRAINT FK_B853CF6A2C6A49BA FOREIGN KEY (idEvent) REFERENCES evenement (idEvent)');
        $this->addSql('ALTER TABLE reservationm ADD CONSTRAINT FK_FA429F5ADF3B0380 FOREIGN KEY (id_maison) REFERENCES maisonh (id_maison)');
        $this->addSql('ALTER TABLE reservationv ADD CONSTRAINT FK_702756B62ABD43F2 FOREIGN KEY (Id) REFERENCES voyage (Id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404BF396750');
        $this->addSql('DROP INDEX id ON reclamation');
        $this->addSql('ALTER TABLE reclamation CHANGE id id_user INT DEFAULT NULL');
        $this->addSql('CREATE INDEX id ON reclamation (id_user)');
        $this->addSql('ALTER TABLE reponsereclamation DROP FOREIGN KEY FK_B052BA70F55AE19E');
        $this->addSql('ALTER TABLE reservationevenement DROP FOREIGN KEY FK_B853CF6A2C6A49BA');
        $this->addSql('ALTER TABLE reservationm DROP FOREIGN KEY FK_FA429F5ADF3B0380');
        $this->addSql('ALTER TABLE reservationv DROP FOREIGN KEY FK_702756B62ABD43F2');
    }
}
