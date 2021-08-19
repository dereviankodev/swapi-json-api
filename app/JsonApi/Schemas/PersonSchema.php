<?php

namespace App\JsonApi\Schemas;

use App\Models\Film;
use App\Models\FilmRepository;
use App\Models\People;
use App\Models\Planet;

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
        'films'
    ];

    protected array $relationships = [
        'planet',
        'films'
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
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships): array
    {
        return [
            'planet' => [
                self::SHOW_SELF => $isPrimary,
                self::SHOW_RELATED => $isPrimary,
                self::SHOW_DATA => isset($includeRelationships['planet']) || !$isPrimary,
                self::DATA => function () use ($resource) {
                    $homeworldData = $resource->getHomeworld();
                    $planet = new Planet();
                    $planet->setId($homeworldData['id']);

                    return $planet;
                }
            ],
            'films' => [
                self::SHOW_SELF => $isPrimary,
                self::SHOW_RELATED => $isPrimary,
                self::SHOW_DATA => isset($includeRelationships['films']) || !$isPrimary,
                self::DATA => function () use ($resource) {
                    $films = $resource->getFilms();
                    if (is_null($films)) {
                        $filmRepository = new FilmRepository();
                        $film = $filmRepository->find($resource->getId());
                        $films = $film->getCharacters();
                    }

                    $filmList = [];

                    foreach ($films as $film) {
                        $people = new Film();
                        $people->setId($film['id']);
                        $filmList[] = $people;
                    }

                    return $filmList;
                }
            ]
        ];
    }
}
