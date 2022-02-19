<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220219225142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE module (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, enabled BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE public.permission (id SERIAL NOT NULL, module_id INT DEFAULT NULL, role VARCHAR(50) NOT NULL, resource VARCHAR(100) NOT NULL, can_create BOOLEAN NOT NULL, can_read BOOLEAN NOT NULL, can_update BOOLEAN NOT NULL, can_delete BOOLEAN NOT NULL, can_index BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_45778BF4AFC2B591 ON public.permission (module_id)');
        $this->addSql('CREATE TABLE public."user" (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email_verified BOOLEAN DEFAULT \'false\' NOT NULL, active BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_327C5DE7E7927C74 ON public."user" (email)');
        $this->addSql('ALTER TABLE public.permission ADD CONSTRAINT FK_45778BF4AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE public.permission DROP CONSTRAINT FK_45778BF4AFC2B591');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE public.permission');
        $this->addSql('DROP TABLE public."user"');
    }
}
