<?php

namespace App\JsonApi\Schemas;

use App\Models\Starship;

class StarshipSchema extends AbstractBaseSchema
{
    /**
     * @var string
     */
    protected $resourceType = 'starships';

    protected array $attributes = [
        'name',
        'model',
        'starship_class',
        'manufacturer',
        'cost_in_credits',
        'length',
        'crew',
        'passengers',
        'max_atmosphering_speed',
        'hyperdrive_rating',
        'MGLT',
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
     * @param Starship $resource
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'name' => $resource->getName(),
            'model' => $resource->getModel(),
            'starship_class' => $resource->getStarshipClass(),
            'manufacturer' => $resource->getManufacturer(),
            'cost_in_credits' => $resource->getCostInCredits(),
            'length' => $resource->getLength(),
            'crew' => $resource->getCrew(),
            'passengers' => $resource->getPassengers(),
            'max_atmosphering_speed' => $resource->getMaxAtmospheringSpeed(),
            'hyperdrive_rating' => $resource->getHyperdriveRating(),
            'MGLT' => $resource->getMGLT(),
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
