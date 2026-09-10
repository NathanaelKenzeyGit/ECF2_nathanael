<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260910071037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absence ADD student_id INT NOT NULL, ADD reason_id INT NOT NULL, ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C959BB1592 FOREIGN KEY (reason_id) REFERENCES absence_reason (id)');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9B03A8386 FOREIGN KEY (created_by_id) REFERENCES `admin` (id)');
        $this->addSql('CREATE INDEX IDX_765AE0C9CB944F1A ON absence (student_id)');
        $this->addSql('CREATE INDEX IDX_765AE0C959BB1592 ON absence (reason_id)');
        $this->addSql('CREATE INDEX IDX_765AE0C9B03A8386 ON absence (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9CB944F1A');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C959BB1592');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9B03A8386');
        $this->addSql('DROP INDEX IDX_765AE0C9CB944F1A ON absence');
        $this->addSql('DROP INDEX IDX_765AE0C959BB1592 ON absence');
        $this->addSql('DROP INDEX IDX_765AE0C9B03A8386 ON absence');
        $this->addSql('ALTER TABLE absence DROP student_id, DROP reason_id, DROP created_by_id');
    }
}
