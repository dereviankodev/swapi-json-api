<?php

namespace App\JsonApi\Planets;

use App\Models\People;
use App\Models\Planet;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
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
    ];

    protected array $relationships = [
        'people',
    ];

    /**
     * @param Planet $resource
     * @return string
     */
    public function getId($resource): string
    {
        return $resource->getId();
    }

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
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships): array
    {
        return [
            'people' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::DATA => function () use ($resource) {
                    $residents = $resource->getResidents();
                    $residentList = [];

                    foreach ($residents as $resident) {
                        $people = new People();
                        $people->setId($resident['id']);
                        $residentList[] = $people;
                    }

                    return $residentList;
                }
            ]
        ];
    }
}
