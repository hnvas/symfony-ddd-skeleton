<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220215213523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE SEQUENCE permission_id_seq');
        $this->addSql('SELECT setval(\'permission_id_seq\', (SELECT MAX(id) FROM permission))');
        $this->addSql('ALTER TABLE permission ALTER id SET DEFAULT nextval(\'permission_id_seq\')');
        $this->addSql('ALTER TABLE "user" ADD email_verified BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD active BOOLEAN DEFAULT \'false\' NOT NULL');
        //$this->addSql('CREATE SEQUENCE user_id_seq');
        $this->addSql('SELECT setval(\'user_id_seq\', (SELECT MAX(id) FROM "user"))');
        $this->addSql('ALTER TABLE "user" ALTER id SET DEFAULT nextval(\'user_id_seq\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP email_verified');
        $this->addSql('ALTER TABLE "user" DROP active');
        //$this->addSql('ALTER TABLE "user" ALTER id DROP DEFAULT');
        //$this->addSql('ALTER TABLE permission ALTER id DROP DEFAULT');
    }
}
