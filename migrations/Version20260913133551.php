<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260913133551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absence CHANGE absence_date absence_date DATE NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_765AE0C9CB944F1ADD8FF9E5 ON absence (student_id, absence_date)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_765AE0C9CB944F1ADD8FF9E5 ON absence');
        $this->addSql('ALTER TABLE absence CHANGE absence_date absence_date DATETIME NOT NULL');
    }
}
