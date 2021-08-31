<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210830083637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle_categorie VARCHAR(255) NOT NULL, description_categorie LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, pourcentage_tva NUMERIC(5, 2) DEFAULT NULL, prix_ttc NUMERIC(5, 2) NOT NULL, date_debut_service DATETIME DEFAULT NULL, date_fin_service DATETIME DEFAULT NULL, statut_pret_service VARCHAR(255) DEFAULT NULL, validation_admin TINYINT(1) NOT NULL, INDEX IDX_E19D9AD2BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status_pret (id INT AUTO_INCREMENT NOT NULL, libelle_status VARCHAR(255) NOT NULL, description_status LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE pret ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE9796BF700BD FOREIGN KEY (status_id) REFERENCES status_pret (id)');
        $this->addSql('CREATE INDEX IDX_52ECE9796BF700BD ON pret (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BCF5E72D');
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE9796BF700BD');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE status_pret');
        $this->addSql('DROP INDEX IDX_52ECE9796BF700BD ON pret');
        $this->addSql('ALTER TABLE pret DROP status_id');
    }
}
