<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;

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
 * @method string getCreated()
 * @method void setCreated(string $created)
 * @method string getEdited()
 * @method void setEdited(string $edited)
 * @method string getUrl()
 * @method void setUrl(string $url)
 *
 * @method array getHomeworld()
 * @method array getFilms()
 */
class People extends BaseModel
{
    public static function create(array $attributes): static
    {
        $people = new static();

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
        $people->setFilms($attributes['films']);

        return $people;
    }

    public function setHomeworld(mixed $homeworld): void
    {
        if (!is_array($homeworld)) {
            $urlPath = parse_url($homeworld, PHP_URL_PATH);
            $explodeUrlPath = explode('/', trim($urlPath, '/'));
            $homeworld = [
                'type' => $explodeUrlPath[1],
                'id' => $explodeUrlPath[2]
            ];
        }

        $this->setAttribute('homeworld', $homeworld);
    }

    public function setFilms(array $films): void
    {
        $filmList = [];
        foreach ($films as $film) {
            if (is_array($film)) {
                $filmList[] = $films;
                break;
            }

            $urlPath = parse_url($film, PHP_URL_PATH);
            $explodeUrlPath = explode('/', trim($urlPath, '/'));
            $filmData = [
                'type' => $explodeUrlPath[1],
                'id' => $explodeUrlPath[2],
            ];
            $filmList[] = $filmData;
        }

        $this->setAttribute('films', $filmList);
    }

    /**
     * @throws JsonApiException
     */
    public function planet(): ?Planet
    {
        $planet = $this->getHomeworld();
        $planetRepository = new PlanetRepository();
        $attributes = $planetRepository->find($planet['id'])->getAttributes();

        if ($relation = Planet::create($attributes)) {
            return $relation;
        }

        return null;
    }

    /**
     * @throws JsonApiException
     */
    public function characters(): array
    {
        $people = $this->getFilms();
        $relations = null;

        foreach ($people as $person) {
            $peopleRepository = new PeopleRepository();
            $attributes = $peopleRepository->find($person['id'])->getAttributes();
            $relations[] = People::create($attributes);
        }

        return $relations;
    }

    public function relationLoaded($arguments): bool
    {
        if (!array_key_exists($arguments, $this->relations)) {
            $peopleRepository = new PeopleRepository();
            $id = $this->getId();
            return $this->relationLoad($peopleRepository, $id);
        }

        return true;
    }
}
