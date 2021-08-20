<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;

class SpeciesRepository extends BaseRepository
{
    protected const RESOURCE_NAME = 'species';

    /**
     * @throws JsonApiException
     */
    public function all($parameters): array
    {
        return $this->getAll(Species::class, $parameters);
    }

    /**
     * @throws JsonApiException
     */
    public function find($resourceId): Species
    {
        return $this->getOne(Species::class, $resourceId);
    }
}
