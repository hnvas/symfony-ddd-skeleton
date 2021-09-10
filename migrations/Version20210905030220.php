<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210905030220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "permission_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "permission" (id INT NOT NULL, role VARCHAR(50) NOT NULL, resource VARCHAR(100) NOT NULL, "create" BOOLEAN NOT NULL, read BOOLEAN NOT NULL, update BOOLEAN NOT NULL, delete BOOLEAN NOT NULL, index BOOLEAN NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "permission_id_seq" CASCADE');
        $this->addSql('DROP TABLE "permission"');
    }
}
