<?php

namespace App\JsonApi\Schemas;

use App\Models\Vehicle;

class VehicleSchema extends AbstractBaseSchema
{
    /**
     * @var string
     */
    protected $resourceType = 'vehicles';

    protected array $attributes = [
        'name',
        'model',
        'vehicle_class',
        'manufacturer',
        'length',
        'cost_in_credits',
        'crew',
        'passengers',
        'max_atmosphering_speed',
        'cargo_capacity',
        'consumables',
        'created',
        'edited',
        // Relationship
        'pilots',
        'films',
    ];

    protected array $relationships = [
        'people',
        'films',
    ];

    /**
     * @param Vehicle $resource
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'name' => $resource->getName(),
            'model' => $resource->getModel(),
            'vehicle_class' => $resource->getVehicleClass(),
            'manufacturer' => $resource->getManufacturer(),
            'length' => $resource->getLength(),
            'cost_in_credits' => $resource->getCostInCredits(),
            'crew' => $resource->getCrew(),
            'passengers' => $resource->getPassengers(),
            'max_atmosphering_speed' => $resource->getMaxAtmospheringSpeed(),
            'cargo_capacity' => $resource->getCargoCapacity(),
            'consumables' => $resource->getConsumables(),
            'created' => $resource->getCreated(),
            'edited' => $resource->getEdited(),

            // Relationship
            'pilots' => $resource->getPilots(),
            'films' => $resource->getFilms(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships): array
    {
        return [
            'people' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['people']),
                self::DATA => function () use ($resource) {
                    return $resource->people(true);
                }
            ],
            'films' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['films']),
                self::DATA => function () use ($resource) {
                    return $resource->films(true);
                }
            ],
        ];
    }
}
