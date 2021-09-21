<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210917105754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE validagence (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, actif VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_9C3CDCCE19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE validagence ADD CONSTRAINT FK_9C3CDCCE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76A76ED395');
        $this->addSql('DROP INDEX UNIQ_880E0D76A76ED395 ON admin');
        $this->addSql('ALTER TABLE admin DROP user_id, DROP nom, DROP prenom, DROP email, DROP password, DROP tel_mobile, DROP adresse');
        $this->addSql('ALTER TABLE client ADD identity_proof VARCHAR(255) DEFAULT NULL, ADD extrait_rcsname VARCHAR(255) DEFAULT NULL, ADD rib VARCHAR(255) DEFAULT NULL, ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD rcs_validated TINYINT(1) DEFAULT NULL, ADD identity_validated TINYINT(1) DEFAULT NULL, ADD rib_validated TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE facture CHANGE montant_ttc_facture montant_ttc_facture NUMERIC(7, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE validagence');
        $this->addSql('ALTER TABLE admin ADD user_id INT NOT NULL, ADD nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD tel_mobile VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76A76ED395 ON admin (user_id)');
        $this->addSql('ALTER TABLE client DROP identity_proof, DROP extrait_rcsname, DROP rib, DROP updated_at, DROP rcs_validated, DROP identity_validated, DROP rib_validated');
        $this->addSql('ALTER TABLE facture CHANGE montant_ttc_facture montant_ttc_facture NUMERIC(5, 2) NOT NULL');
    }
}
