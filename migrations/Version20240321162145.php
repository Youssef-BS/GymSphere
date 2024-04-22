<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321162145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD program_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA73EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA73EB8070A ON event (program_id)');
        $this->addSql('ALTER TABLE exercice ADD program_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D3EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('CREATE INDEX IDX_E418C74D3EB8070A ON exercice (program_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA73EB8070A');
        $this->addSql('DROP INDEX IDX_3BAE0AA73EB8070A ON event');
        $this->addSql('ALTER TABLE event DROP program_id');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D3EB8070A');
        $this->addSql('DROP INDEX IDX_E418C74D3EB8070A ON exercice');
        $this->addSql('ALTER TABLE exercice DROP program_id');
    }
}
