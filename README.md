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
`SchemaProvider` interface. So here's the trick. We need to get `OrmSchemaProvider`, and use it to populate schema. Then we could
pass this schema to someone else so it could add adittional relations.

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

In our legacy schema definition we will just create dummy table:

```
public function define(Schema $schema): void
{
    $legacyTable = $schema->createTable('legacy_table');

    $legacyTable->addColumn('id', 'integer', []);
    $legacyTable->addColumn('name', 'string');
    $legacyTable->setPrimaryKey(['id']);
}
```

### Redefining CLI command

By default Doctrine Migration Bundle will not work with custom schema providers. At least until [this PR](https://github.com/doctrine/DoctrineMigrationsBundle/pull/190) will be available in release version. So we need to "copy" this command ([MigrationDiffCommand.php](src/Command/Doctrine/MigrationDiffCommand.php)).
Then we will just register new service, which will override original command. Also we need to pass our schema provider as
dependency to our new command:

```php
$c->register(MigrationDiffCommand::class)
  ->addArgument(new Reference(ExtendedSchemaProvider::class))
  ->addTag('console.command');
```

That's pretty much all. Now we could run `doctrine:migration:diff` and we will get all changes in our schema. You could
see example migration file in [configs](configs/migrations) directory.
