<?php

namespace App\Models;

/**
 * @var int $id
 * @var string|null $name
 * @var string|null $diameter
 * @var string|null $rotation_period
 * @var string|null $orbital_period
 * @var string|null $gravity
 * @var string|null $population
 * @var string|null $climate
 * @var string|null $terrain
 * @var string|null $surface_water
 * @var string|null $created
 * @var string|null $edited
 * @var string|null $url
 */
class Planet extends BaseModel
{
    public static function create(array $attributes): self
    {
        $planet = new self();

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

        return $planet;
    }

    #-----------------------------------------------------------------
    # Accessors and Mutators
    #-----------------------------------------------------------------

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public function setId(string $url): void
    {
        $this->setAttribute('id', basename($url));
    }

    public function getName(): string|null
    {
        return $this->getAttribute('name');
    }

    public function setName(string $name): void
    {
        $this->setAttribute('name', $name);
    }

//    public function setDiameter(string $diameter)
//    {
//    }
//
//    public function setRotationPeriod(string $rotation_period)
//    {
//    }
}
