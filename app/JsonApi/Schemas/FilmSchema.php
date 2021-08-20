<?php

namespace App\JsonApi\Schemas;

use App\Models\Film;

class FilmSchema extends AbstractBaseSchema
{
    /**
     * @var string
     */
    protected $resourceType = 'films';

    protected array $attributes = [
        'title',
        'episode_id',
        'opening_crawl',
        'director',
        'producer',
        'release_date',
        'created',
        'edited',
        // Relationship
        'characters',
        'planets',
        'species',
        'starships',
        'vehicles',
    ];

    protected array $relationships = [
        'people',
        'planets',
        'species',
        'starships',
        'vehicles',
    ];

    /**
     * @param Film $resource
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'title' => $resource->getTitle(),
            'episode_id' => $resource->getEpisodeId(),
            'opening_crawl' => $resource->getOpeningCrawl(),
            'director' => $resource->getDirector(),
            'producer' => $resource->getProducer(),
            'release_date' => $resource->getReleaseDate(),
            'created' => $resource->getCreated(),
            'edited' => $resource->getEdited(),
            // Relationship
            'characters' => $resource->getCharacters(),
            'planets' => $resource->getPlanets(),
            'species' => $resource->getSpecies(),
            'starships' => $resource->getStarships(),
            'vehicles' => $resource->getVehicles(),
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
            'planets' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['planets']),
                self::DATA => function () use ($resource) {
                    return $resource->planets(true);
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
