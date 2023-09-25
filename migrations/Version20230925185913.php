<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230925185913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inter (id INT AUTO_INCREMENT NOT NULL, technicien_id INT DEFAULT NULL, date DATETIME NOT NULL, anomalie VARCHAR(255) DEFAULT NULL, salaire DOUBLE PRECISION DEFAULT NULL, INDEX IDX_7C802D6F13457256 (technicien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inter ADD CONSTRAINT FK_7C802D6F13457256 FOREIGN KEY (technicien_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD telephone VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inter DROP FOREIGN KEY FK_7C802D6F13457256');
        $this->addSql('DROP TABLE inter');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP created_at, DROP telephone');
    }
}
