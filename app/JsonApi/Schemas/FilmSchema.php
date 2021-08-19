<?php

namespace App\JsonApi\Schemas;

use App\Models\Film;
use App\Models\FilmRepository;
use App\Models\People;

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
    ];

    protected array $relationships = [
        'people',
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
                    $residents = $resource->getfilms();
                    if (is_null($residents)) {
                        $planetRepository = new FilmRepository();
                        $planet = $planetRepository->find($resource->getId());
                        $residents = $planet->getCharacters();
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
}
