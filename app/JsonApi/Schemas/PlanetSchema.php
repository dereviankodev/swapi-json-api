<?php

namespace App\JsonApi\Schemas;

use App\Models\People;
use App\Models\Planet;
use App\Models\PlanetRepository;
use Neomerx\JsonApi\Schema\SchemaProvider;

class PlanetSchema extends SchemaProvider
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
                self::SHOW_SELF => $isPrimary,
                self::SHOW_RELATED => $isPrimary,
                self::SHOW_DATA => isset($includeRelationships['people']) || !$isPrimary,
                self::DATA => function () use ($resource) {
                    $residents = $resource->getResidents();
                    if (is_null($residents)) {
                        $planetRepository = new PlanetRepository();
                        $planet = $planetRepository->find($resource->getId());
                        $residents = $planet->getResidents();
                    }
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

    public function getIncludedResourceLinks($resource): array
    {
        return parent::getResourceLinks($resource);
    }
}
