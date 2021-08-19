<?php

namespace App\JsonApi\Schemas;

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
    ];

    protected array $relationships = [
        'planet',
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
            ]
        ];
    }
}
