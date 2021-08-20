<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;

class PlanetRepository extends BaseRepository
{
    protected const RESOURCE_NAME = 'planets';

    /**
     * @throws JsonApiException
     */
    public function all($parameters): array
    {
        return $this->getAll(Planet::class, $parameters);
    }

    /**
     * @throws JsonApiException
     */
    public function find($resourceId): Planet
    {
        return $this->getOne(Planet::class, $resourceId);
    }
}
