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
        return $resource->getName();
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
            'homeworld' => $resource->getHomeworld(),
            'created' => $resource->getCreated(),
            'edited' => $resource->getEdited(),
        ];
    }
}
