<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;

class PeopleRepository extends BaseRepository
{
    protected const RESOURCE_NAME = 'people';

    /**
     * @throws JsonApiException
     */
    public function all($parameters): array
    {
        return $this->getAll(People::class, $parameters);
    }

    /**
     * @throws JsonApiException
     */
    public function find($resourceId): People
    {
        return $this->getOne(People::class, $resourceId);
    }
}
