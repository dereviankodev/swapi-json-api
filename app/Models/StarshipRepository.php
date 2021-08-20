<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;

class StarshipRepository extends BaseRepository
{
    protected const RESOURCE_NAME = 'starships';

    /**
     * @throws JsonApiException
     */
    public function all($parameters): array
    {
        return $this->getAll(Starship::class, $parameters);
    }

    /**
     * @throws JsonApiException
     */
    public function find($resourceId): Starship
    {
        return $this->getOne(Starship::class, $resourceId);
    }
}
