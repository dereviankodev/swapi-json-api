<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;

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
 * @method array getResidents()
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

        return $planet;
    }

    public function setResidents(array $residents): void
    {
        $residentList = [];
        foreach ($residents as $resident) {
            if (is_array($resident)) {
                $residentList[] = $residents;
                break;
            }

            $urlPath = parse_url($resident, PHP_URL_PATH);
            $explodeUrlPath = explode('/', trim($urlPath, '/'));
            $residentData = [
                'type' => $explodeUrlPath[1],
                'id' => $explodeUrlPath[2],
            ];
            $residentList[] = $residentData;
        }

        $this->setAttribute('residents', $residentList);
    }

    /**
     * @throws JsonApiException
     */
    public function people(): array
    {
        $people = $this->getResidents();
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
            $peopleRepository = new PlanetRepository();
            $id = $this->getId();
            return $this->relationLoad($peopleRepository, $id);
        }

        return true;
    }
}
