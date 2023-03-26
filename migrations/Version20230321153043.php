<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321153043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domain (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, icon_url VARCHAR(255) DEFAULT NULL, INDEX IDX_A7A91E0B7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lift (id INT AUTO_INCREMENT NOT NULL, station_id INT NOT NULL, name VARCHAR(255) NOT NULL, first_hour TIME NOT NULL, last_hour TIME NOT NULL, exception TINYINT(1) DEFAULT NULL, exception_message VARCHAR(255) DEFAULT NULL, INDEX IDX_737D1E0C21BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slope (id INT AUTO_INCREMENT NOT NULL, station_id INT NOT NULL, name VARCHAR(255) NOT NULL, difficulty INT NOT NULL, first_hour TIME NOT NULL, last_hour TIME NOT NULL, exception TINYINT(1) DEFAULT NULL, exception_message VARCHAR(255) DEFAULT NULL, INDEX IDX_58CC458521BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, domain_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, icon_url VARCHAR(255) DEFAULT NULL, INDEX IDX_9F39F8B17E3C61F9 (owner_id), INDEX IDX_9F39F8B1115F0EE5 (domain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE domain ADD CONSTRAINT FK_A7A91E0B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lift ADD CONSTRAINT FK_737D1E0C21BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE slope ADD CONSTRAINT FK_58CC458521BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE station ADD CONSTRAINT FK_9F39F8B17E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE station ADD CONSTRAINT FK_9F39F8B1115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain DROP FOREIGN KEY FK_A7A91E0B7E3C61F9');
        $this->addSql('ALTER TABLE lift DROP FOREIGN KEY FK_737D1E0C21BDB235');
        $this->addSql('ALTER TABLE slope DROP FOREIGN KEY FK_58CC458521BDB235');
        $this->addSql('ALTER TABLE station DROP FOREIGN KEY FK_9F39F8B17E3C61F9');
        $this->addSql('ALTER TABLE station DROP FOREIGN KEY FK_9F39F8B1115F0EE5');
        $this->addSql('DROP TABLE domain');
        $this->addSql('DROP TABLE lift');
        $this->addSql('DROP TABLE slope');
        $this->addSql('DROP TABLE station');
        $this->addSql('DROP TABLE user');
    }
}
