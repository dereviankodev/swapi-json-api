<?php

namespace App\JsonApi\Schemas;

use App\Models\People;

class PersonSchema extends AbstractBaseSchema
{
    /**
     * @var string
     */
    protected $resourceType = 'people';

    protected array $attributes = [
        'name',
        'birth_year',
        'eye_color',
        'gender',
        'hair_color',
        'height',
        'mass',
        'skin_color',
        'created',
        'edited',
        // Relationship
        'homeworld',
        'films',
        'species',
        'starships',
        'vehicles',
    ];

    protected array $relationships = [
        'planet',
        'films',
        'species',
        'starships',
        'vehicles',
    ];

    /**
     * @param People $resource
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'name' => $resource->getName(),
            'birth_year' => $resource->getBirthYear(),
            'eye_color' => $resource->getEyeColor(),
            'gender' => $resource->getGender(),
            'hair_color' => $resource->getHairColor(),
            'height' => $resource->getHeight(),
            'mass' => $resource->getMass(),
            'skin_color' => $resource->getSkinColor(),
            'created' => $resource->getCreated(),
            'edited' => $resource->getEdited(),
            // Relationship
            'homeworld' => $resource->getHomeworld(),
            'films' => $resource->getFilms(),
            'species' => $resource->getSpecies(),
            'starships' => $resource->getStarships(),
            'vehicles' => $resource->getVehicles(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships): array
    {
        return [
            'planet' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['planet']),
                self::DATA => function () use ($resource) {
                    return $resource->planet(true);
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
            'species' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['species']),
                self::DATA => function () use ($resource) {
                    return $resource->species(true);
                }
            ],
            'starships' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['starships']),
                self::DATA => function () use ($resource) {
                    return $resource->starships(true);
                }
            ],
            'vehicles' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['vehicles']),
                self::DATA => function () use ($resource) {
                    return $resource->vehicles(true);
                }
            ],
        ];
    }
}
