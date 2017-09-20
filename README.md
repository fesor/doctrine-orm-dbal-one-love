Example on how to generate migration from multiple schema providers
============================

This repository contains example of how to deal with multiple schema providers.

## Background

Let's say that we have simple symfony + doctrine application. Most of our schema was managed via entities and it's mappings.

For some use case we don't really need entities. What we really want is to work directly via dbal with few tables. There could
be different reasons for that. Performance, decoupling, bridging existing infrastructure and so on.

For our symfony application we are managing changes in schema by simply calling `doctrine:migration:diff` or `doctrine:schema:update` if you wish.
So for this "unmanaged" part of schema we also want this to work.

## How it's works

 