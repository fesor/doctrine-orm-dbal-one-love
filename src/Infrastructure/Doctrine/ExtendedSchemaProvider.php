<?php

namespace Fesor\SchemaExample\Infrastructure\Doctrine;

use Doctrine\DBAL\Migrations\Provider\OrmSchemaProvider;
use Doctrine\DBAL\Migrations\Provider\SchemaProviderInterface;

class ExtendedSchemaProvider implements SchemaProviderInterface
{
    /**
     * @var OrmSchemaProvider
     */
    private $ormSchemaProvider;
    /**
     * @var LegacySchemaDefinition
     */
    private $legacySchema;

    public function __construct(OrmSchemaProvider $ormSchemaProvider, LegacySchemaDefinition $legacySchema)
    {
        $this->ormSchemaProvider = $ormSchemaProvider;
        $this->legacySchema = $legacySchema;
    }

    public function createSchema()
    {
        $schema = $this->ormSchemaProvider->createSchema();
        $this->legacySchema->define($schema);

        return $schema;
    }
}