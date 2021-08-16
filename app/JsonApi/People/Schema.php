<?php

namespace App\JsonApi\People;

use App\Models\People;
use App\Models\Planet;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
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
    ];

    protected array $relationships = [
        'planet',
    ];

    /**
     * @param People $resource
     * @return string
     */
    public function getId($resource): string
    {
        return $resource->getId();
    }

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
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships): array
    {
        return [
            'planet' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => true,
                self::DATA => function () use ($resource) {
                    $homeworldData = $resource->getHomeworld();
                    $planet = new Planet();
                    $planet->setId($homeworldData['id']);

                    return $planet;
                }
            ]
        ];
    }
}
