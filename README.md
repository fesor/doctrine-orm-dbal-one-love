Example on how to generate migration from multiple schema providers
============================

This repository contains example of how to deal with multiple schema providers.

## Background

Let's say that we have simple symfony + doctrine application. Most of our schema was managed via entities and it's mappings.

For some use case we don't really need entities. What we really want is to work directly via dbal with few tables. There could
be different reasons for that. Performance, decoupling, bridging existing infrastructure and so on.

For our symfony application we are managing changes in schema by simply calling `doctrine:migration:diff` or `doctrine:schema:update` if you wish.
So for this "unmanaged" part of schema we also want this to work.

### Assumptions

 - We are not going to use multiple entity managers.

## How to make it work

If you will look at how "migration:diff" command is implemented, it allows you to provide any instance that implements
`SchemaProvider` interface. So heres trick. We need to get `OrmSchemaProvider`, and use it to populate schema. Then we could
pass this schema into somewhere else so we could add adittional relations.

```php
class ExtendedSchemaProvider implements SchemaProviderInterface
{
    // ...
    public function __construct(OrmSchemaProvider $ormSchemaProvider, LegacySchemaProvider $legacySchemaProvider)
    {
        $this->ormSchemaProvider = $ormSchemaProvider;
        $this->legacySchemaProvider = $legacySchemaProvider;
    }

    public function createSchema()
    {
        $schema = $this->ormSchemaProvider->createSchema();
        $this->legacySchema->define($ormSchema);

        return $schema;
    }
}
```

In our legacy schema we will just create dummy table:

```
public function define(Schema $schema)
{
    $legacyTable = $schema->createTable('legacy_table');

    $legacyTable->addColumn('id', 'integer', []);
    $legacyTable->setPrimaryKey(['id']);

    return $legacyTable;
}
```

### Redefining CLI command



