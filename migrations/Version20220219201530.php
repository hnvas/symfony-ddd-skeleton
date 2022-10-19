<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220219201530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE OR REPLACE FUNCTION reset_sequence() RETURNS trigger AS
            $$
            DECLARE
                table_name    TEXT := TG_TABLE_NAME;
                sequence_name TEXT;
                query         TEXT;
            BEGIN
                SELECT concat(table_name, '_id_seq') INTO sequence_name;
                SELECT format('ALTER SEQUENCE %I RESTART', sequence_name) into query;
            
                EXECUTE query;
                RETURN NULL;
            END;
            $$ LANGUAGE plpgsql;
        ");

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP FUNCTION reset_sequence');
    }
}
