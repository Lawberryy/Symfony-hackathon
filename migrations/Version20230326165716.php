<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326165716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat_history (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, sent_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6BB4BC22F624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_history ADD CONSTRAINT FK_6BB4BC22F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lift ADD type VARCHAR(255) DEFAULT NULL, ADD peak_hour DATETIME DEFAULT NULL, ADD comfort INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slope ADD peak_hour DATETIME DEFAULT NULL, ADD snow_quality INT DEFAULT NULL');
        $this->addSql('ALTER TABLE station ADD weather VARCHAR(255) DEFAULT NULL, ADD notation INT DEFAULT NULL, CHANGE domain_id domain_id INT DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_history DROP FOREIGN KEY FK_6BB4BC22F624B39D');
        $this->addSql('DROP TABLE chat_history');
        $this->addSql('ALTER TABLE lift DROP type, DROP peak_hour, DROP comfort');
        $this->addSql('ALTER TABLE slope DROP peak_hour, DROP snow_quality');
        $this->addSql('ALTER TABLE station DROP weather, DROP notation, CHANGE domain_id domain_id INT NOT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
    }
}
