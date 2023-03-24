<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324093956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trail ADD owner_id INT NOT NULL, ADD is_completed TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE trail ADD CONSTRAINT FK_B268858F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B268858F7E3C61F9 ON trail (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trail DROP FOREIGN KEY FK_B268858F7E3C61F9');
        $this->addSql('DROP INDEX IDX_B268858F7E3C61F9 ON trail');
        $this->addSql('ALTER TABLE trail DROP owner_id, DROP is_completed');
    }
}
