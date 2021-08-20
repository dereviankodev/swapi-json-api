<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;

class FilmRepository extends BaseRepository
{
    protected const RESOURCE_NAME = 'films';

    /**
     * @throws JsonApiException
     */
    public function all($parameters): array
    {
        return $this->getAll(Film::class, $parameters);
    }

    /**
     * @throws JsonApiException
     */
    public function find($resourceId): Film
    {
        return $this->getOne(Film::class, $resourceId);
    }
}
