<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902052828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnememnt DROP FOREIGN KEY FK_6B87664619EB6921');
        $this->addSql('DROP INDEX UNIQ_6B87664619EB6921 ON abonnememnt');
        $this->addSql('ALTER TABLE abonnememnt DROP client_id');
        $this->addSql('ALTER TABLE abonnement ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_351268BB19EB6921 ON abonnement (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnememnt ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE abonnememnt ADD CONSTRAINT FK_6B87664619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B87664619EB6921 ON abonnememnt (client_id)');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB19EB6921');
        $this->addSql('DROP INDEX UNIQ_351268BB19EB6921 ON abonnement');
        $this->addSql('ALTER TABLE abonnement DROP client_id');
    }
}
