<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220123224842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "permission_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "permission" (id INT NOT NULL, role VARCHAR(50) NOT NULL, resource VARCHAR(100) NOT NULL, can_create BOOLEAN NOT NULL, can_read BOOLEAN NOT NULL, can_update BOOLEAN NOT NULL, can_delete BOOLEAN NOT NULL, can_index BOOLEAN NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "permission_id_seq" CASCADE');
        $this->addSql('DROP TABLE "permission"');
    }
}
