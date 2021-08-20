<?php

namespace App\JsonApi\Schemas;

use App\Models\Planet;

class PlanetSchema extends AbstractBaseSchema
{
    /**
     * @var string
     */
    protected $resourceType = 'planets';

    protected array $attributes = [
        'name',
        'diameter',
        'rotation_period',
        'orbital_period',
        'gravity',
        'population',
        'climate',
        'terrain',
        'surface_water',
        'created',
        'edited',
        // Relationship
        'residents',
        'films',
    ];

    protected array $relationships = [
        'people',
        'films',
    ];

    /**
     * @param Planet $resource
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'name' => $resource->getName(),
            'diameter' => $resource->getDiameter(),
            'rotation_period' => $resource->getRotationPeriod(),
            'orbital_period' => $resource->getOrbitalPeriod(),
            'gravity' => $resource->getGravity(),
            'population' => $resource->getPopulation(),
            'climate' => $resource->getClimate(),
            'terrain' => $resource->getTerrain(),
            'surface_water' => $resource->getSurfaceWater(),
            'created' => $resource->getCreated(),
            'edited' => $resource->getEdited(),
            // Relationship
            'residents' => $resource->getResidents(),
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
