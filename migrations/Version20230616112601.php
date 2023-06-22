<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230616112601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD cover_id INT DEFAULT NULL, DROP cover, DROP file_path');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9922726E9 FOREIGN KEY (cover_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_169E6FB9922726E9 ON course (cover_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9922726E9');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP INDEX IDX_169E6FB9922726E9 ON course');
        $this->addSql('ALTER TABLE course ADD cover VARCHAR(255) DEFAULT NULL, ADD file_path VARCHAR(255) DEFAULT NULL, DROP cover_id');
    }
}
