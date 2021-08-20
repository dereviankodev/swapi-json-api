<?php

namespace App\Models;

/**
 * @method int getId()
 * @method string getName()
 * @method void setName(string $name)
 * @method string getDiameter()
 * @method void setDiameter(string $diameter)
 * @method string getRotationPeriod()
 * @method void setRotationPeriod(string $rotation_period)
 * @method string getOrbitalPeriod()
 * @method void setOrbitalPeriod(string $orbital_period)
 * @method string getGravity()
 * @method void setGravity(string $gravity)
 * @method string getPopulation()
 * @method void setPopulation(string $population)
 * @method string getClimate()
 * @method void setClimate(string $climate)
 * @method string getTerrain()
 * @method void setTerrain(string $terrain)
 * @method string getSurfaceWater()
 * @method void setSurfaceWater(string $surface_water)
 * @method string getCreated()
 * @method void setCreated(string $created)
 * @method string getEdited()
 * @method void setEdited(string $edited)
 * @method string getUrl()
 * @method void setUrl(string $url)
 *
 * @method array getResidents() Has many
 * @method array getFilms() Has many
 */
class Planet extends BaseModel
{
    public static function create(array $attributes): static
    {
        $planet = new static();

        $planet->setId($attributes['url']);
        $planet->setName($attributes['name']);
        $planet->setDiameter($attributes['diameter']);
        $planet->setRotationPeriod($attributes['rotation_period']);
        $planet->setOrbitalPeriod($attributes['orbital_period']);
        $planet->setGravity($attributes['gravity']);
        $planet->setPopulation($attributes['population']);
        $planet->setClimate($attributes['climate']);
        $planet->setTerrain($attributes['terrain']);
        $planet->setSurfaceWater($attributes['surface_water']);
        $planet->setCreated($attributes['created']);
        $planet->setEdited($attributes['edited']);
        $planet->setUrl($attributes['url']);

        // Relationship
        $planet->setResidents($attributes['residents']);
        $planet->setFilms($attributes['films']);

        return $planet;
    }

    // Relation Mutators

    public function setResidents(array $dataList): void
    {
        $this->setAttribute('residents', $this->getParsedDataList($dataList));
    }

    public function setFilms(array $dataList): void
    {
        $this->setAttribute('films', $this->getParsedDataList($dataList));
    }

    // Relationship data

    public function people(bool $simple = false): ?array
    {
        $relatedData = $this->getResidents();
        return $this->getHasMany(People::class, PeopleRepository::class, $relatedData, $simple);
    }

    public function films(bool $simple = false): ?array
    {
        $relatedData = $this->getFilms();
        return $this->getHasMany(Film::class, FilmRepository::class, $relatedData, $simple);
    }

    public function relationLoaded($arguments): bool
    {
        return array_key_exists($arguments, $this->relations) || $this->relationLoad(new PlanetRepository(), $this->getId());
    }
}
