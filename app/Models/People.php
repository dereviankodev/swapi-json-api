<?php

namespace App\Models;

use Illuminate\Support\Str;

/**
 * @method int getId()
 * @method string getName()
 * @method void setName(string $name)
 * @method string getBirthYear()
 * @method void setBirthYear(string $birth_year)
 * @method string getEyeColor()
 * @method void setEyeColor(string $eye_color)
 * @method string getGender()
 * @method void setGender(string $gender)
 * @method string getHairColor()
 * @method void setHairColor(string $hair_color)
 * @method string getHeight()
 * @method void setHeight(string $height)
 * @method string getMass()
 * @method void setMass(string $mass)
 * @method string getSkinColor()
 * @method void setSkinColor(string $skin_color)
 * @method array getHomeworld()
 * @method string getCreated()
 * @method void setCreated(string $created)
 * @method string getEdited()
 * @method void setEdited(string $edited)
 * @method string getUrl()
 * @method void setUrl(string $url)
 */
class People extends BaseModel
{
    /**
     * @return string|void
     */
    public function __call(string $name, array $arguments)
    {
        $startsWith = substr($name, 0, 3);
        $methodName = Str::snake(substr($name, 3));

        $match = match ($startsWith) {
            'set' => $this->setAttribute($methodName, $arguments[0]),
            'get' => $this->getAttribute($methodName)
        };

        if (is_string($match) || is_array($match)) {
            return $match;
        }
    }

    public static function create(array $attributes): self
    {
        $people = new self();

        $people->setId($attributes['url']);
        $people->setName($attributes['name']);
        $people->setBirthYear($attributes['birth_year']);
        $people->setEyeColor($attributes['eye_color']);
        $people->setGender($attributes['gender']);
        $people->setHairColor($attributes['hair_color']);
        $people->setHeight($attributes['height']);
        $people->setMass($attributes['mass']);
        $people->setSkinColor($attributes['skin_color']);
        $people->setCreated($attributes['created']);
        $people->setEdited($attributes['edited']);
        $people->setUrl($attributes['url']);
        // Relationship
        $people->setHomeworld($attributes['homeworld']);

        return $people;
    }

    public function setId(string $url): void
    {
        $this->setAttribute('id', basename($url));
    }

    public function setHomeworld(string $homeworld): void
    {
        $urlPath = parse_url($homeworld, PHP_URL_PATH);
        $explodeUrlPath = explode('/', trim($urlPath, '/'));
        $homeworld = [
            'type' => $explodeUrlPath[1],
            'id' => $explodeUrlPath[2]
        ];

        $this->setAttribute('homeworld', $homeworld);
    }
}
