<?php

namespace App\JsonApi\Schemas;

use App\Models\Species;

class SpeciesSchema extends AbstractBaseSchema
{
    /**
     * @var string
     */
    protected $resourceType = 'species';

    protected array $attributes = [
        'name',
        'classification',
        'designation',
        'average_height',
        'average_lifespan',
        'eye_colors',
        'hair_colors',
        'skin_colors',
        'language',
        'created',
        'edited',
        // Relationship
        'homeworld',
        'people',
        'films',
    ];

    protected array $relationships = [
        'planet',
        'people',
        'films',
    ];

    /**
     * @param Species $resource
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'name' => $resource->getName(),
            'classification' => $resource->getClassification(),
            'designation' => $resource->getDesignation(),
            'average_height' => $resource->getAverageHeight(),
            'average_lifespan' => $resource->getAverageLifespan(),
            'eye_colors' => $resource->getEyeColors(),
            'hair_colors' => $resource->getHairColors(),
            'skin_color' => $resource->getSkinColors(),
            'language' => $resource->getLanguage(),
            'created' => $resource->getCreated(),
            'edited' => $resource->getEdited(),
            // Relationship
            'homeworld' => $resource->getHomeworld(),
            'people' => $resource->getPeople(),
            'films' => $resource->getFilms(),
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
