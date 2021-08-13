<?php

namespace App\JsonApi\People;

use App\Models\People;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'people';

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
            'homeworld' => $resource->getHomeworld(),
            'created' => $resource->getCreated(),
            'edited' => $resource->getEdited(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'planet' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
//                self::DATA => function () use ($resource) {
//                    return basename($resource->getHomeworld());
//                }
            ]
        ];
    }
}
