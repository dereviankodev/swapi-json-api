<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

abstract class AbstractBaseSchema extends SchemaProvider
{
    public function getId($resource): string
    {
        return $resource->getId();
    }

    public function getIncludedResourceLinks($resource): array
    {
        return parent::getResourceLinks($resource);
    }
}
