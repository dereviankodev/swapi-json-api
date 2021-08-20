<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;

class VehicleRepository extends BaseRepository
{
    protected const RESOURCE_NAME = 'vehicles';

    /**
     * @throws JsonApiException
     */
    public function all($parameters): array
    {
        return $this->getAll(Vehicle::class, $parameters);
    }

    /**
     * @throws JsonApiException
     */
    public function find($resourceId): Vehicle
    {
        return $this->getOne(Vehicle::class, $resourceId);
    }
}
