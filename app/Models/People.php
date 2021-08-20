<?php

namespace App\Models;

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
 * @method array getHomeworld() Has one
 * @method array getFilms() Has many
 * @method array getSpecies() Has many
 * @method array getStarships() Has many
 * @method array getVehicles() Has many
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
        $people->setSpecies($attributes['species']);
        $people->setStarships($attributes['starships']);
        $people->setVehicles($attributes['vehicles']);

        return $people;
    }

    // Relation Mutators

    public function setHomeworld(mixed $data): void
    {
        $this->setAttribute('homeworld', $this->getParsedData($data));
    }

    public function setFilms(array $dataList): void
    {
        $this->setAttribute('films', $this->getParsedDataList($dataList));
    }

    public function setSpecies(array $dataList): void
    {
        $this->setAttribute('species', $this->getParsedDataList($dataList));
    }

    public function setStarships(array $dataList): void
    {
        $this->setAttribute('starships', $this->getParsedDataList($dataList));
    }

    public function setVehicles(array $dataList): void
    {
        $this->setAttribute('vehicles', $this->getParsedDataList($dataList));
    }

    // Relationship data

    public function planet(bool $simple = false): object|array|null
    {
        $relatedData = $this->getHomeworld();
        return $this->getHasOne(Planet::class, PlanetRepository::class, $relatedData, $simple);
    }

    public function films(bool $simple = false): ?array
    {
        $relatedData = $this->getFilms();
        return $this->getHasMany(Film::class, FilmRepository::class, $relatedData, $simple);
    }

    public function species(bool $simple = false): ?array
    {
        $relatedData = $this->getSpecies();
        return $this->getHasMany(Species::class, SpeciesRepository::class, $relatedData, $simple);
    }

    public function starships(bool $simple = false): ?array
    {
        $relatedData = $this->getStarships();
        return $this->getHasMany(Starship::class, StarshipRepository::class, $relatedData, $simple);
    }

    public function vehicles(bool $simple = false): ?array
    {
        $relatedData = $this->getVehicles();
        return $this->getHasMany(Vehicle::class, VehicleRepository::class, $relatedData, $simple);
    }

    public function relationLoaded($arguments): bool
    {
        return array_key_exists($arguments, $this->relations) || $this->relationLoad(new PeopleRepository(), $this->getId());
    }
}
