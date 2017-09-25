<?php

namespace Fesor\SchemaExample\Infrastructure\Doctrine;

use Doctrine\DBAL\Migrations\Provider\SchemaProviderInterface;
use Doctrine\DBAL\Schema\Schema;

class LegacySchemaDefinition
{
    public function define(Schema $schema): void
    {
        $legacyTable = $schema->createTable('legacy_table');

        $legacyTable->addColumn('id', 'integer', []);
        $legacyTable->addColumn('name', 'string');
        $legacyTable->setPrimaryKey(['id']);
    }
}