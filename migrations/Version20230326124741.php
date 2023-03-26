<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326124741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE problem (id INT AUTO_INCREMENT NOT NULL, station_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_D7E7CCC821BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE problem ADD CONSTRAINT FK_D7E7CCC821BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE chat_history ADD CONSTRAINT FK_6BB4BC22F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lift ADD type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE station ADD weather VARCHAR(255) DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE problem DROP FOREIGN KEY FK_D7E7CCC821BDB235');
        $this->addSql('DROP TABLE problem');
        $this->addSql('ALTER TABLE chat_history DROP FOREIGN KEY FK_6BB4BC22F624B39D');
        $this->addSql('ALTER TABLE station DROP weather, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lift DROP type');
    }
}
