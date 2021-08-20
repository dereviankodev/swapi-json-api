<?php

namespace App\Models;

/**
 * @method int getId()
 * @method string getName()
 * @method void setName(string $name)
 * @method string getModel()
 * @method void setModel(string $model)
 * @method string getStarshipClass()
 * @method void setStarshipClass(string $starship_class)
 * @method string getManufacturer()
 * @method void setManufacturer(string $manufacturer)
 * @method string getCostInCredits()
 * @method void setCostInCredits(string $cost_in_credits)
 * @method string getLength()
 * @method void setLength(string $length)
 * @method string getCrew()
 * @method void setCrew(string $crew)
 * @method string getPassengers()
 * @method void setPassengers(string $passengers)
 * @method string getMaxAtmospheringSpeed()
 * @method void setMaxAtmospheringSpeed(string $max_atmosphering_speed)
 * @method string getHyperdriveRating()
 * @method void setHyperdriveRating(string $hyperdrive_rating)
 * @method string getMGLT()
 * @method void setMGLT(string $MGLT)
 * @method string getCargoCapacity()
 * @method void setCargoCapacity(string $cargo_capacity)
 * @method string getConsumables()
 * @method void setConsumables(string $consumables)
 * @method string getCreated()
 * @method void setCreated(string $created)
 * @method string getEdited()
 * @method void setEdited(string $edited)
 * @method string getUrl()
 * @method void setUrl(string $url)
 *
 * @method array getPilots() Has many
 * @method array getFilms() Has many
 */
class Starship extends BaseModel
{
    public static function create(array $attributes): static
    {
        $planet = new static();

        $planet->setId($attributes['url']);
        $planet->setName($attributes['name']);
        $planet->setModel($attributes['model']);
        $planet->setStarshipClass($attributes['starship_class']);
        $planet->setManufacturer($attributes['manufacturer']);
        $planet->setCostInCredits($attributes['cost_in_credits']);
        $planet->setLength($attributes['length']);
        $planet->setCrew($attributes['crew']);
        $planet->setPassengers($attributes['passengers']);
        $planet->setMaxAtmospheringSpeed($attributes['max_atmosphering_speed']);
        $planet->setHyperdriveRating($attributes['hyperdrive_rating']);
        $planet->setMGLT($attributes['MGLT'] ?? $attributes['m_g_l_t']);
        $planet->setCargoCapacity($attributes['cargo_capacity']);
        $planet->setConsumables($attributes['consumables']);
        $planet->setCreated($attributes['created']);
        $planet->setEdited($attributes['edited']);
        $planet->setUrl($attributes['url']);

        // Relationship
        $planet->setPilots($attributes['pilots']);
        $planet->setFilms($attributes['films']);

        return $planet;
    }

    // Relation Mutators

    public function setPilots(array $dataList): void
    {
        $this->setAttribute('pilots', $this->getParsedDataList($dataList));
    }

    public function setFilms(array $dataList): void
    {
        $this->setAttribute('films', $this->getParsedDataList($dataList));
    }

    // Relationship data

    public function people(bool $simple = false): ?array
    {
        $relatedData = $this->getPilots();
        return $this->getHasMany(People::class, PeopleRepository::class, $relatedData, $simple);
    }

    public function films(bool $simple = false): ?array
    {
        $relatedData = $this->getFilms();
        return $this->getHasMany(Film::class, FilmRepository::class, $relatedData, $simple);
    }

    public function relationLoaded($arguments): bool
    {
        return array_key_exists($arguments, $this->relations) || $this->relationLoad(new StarshipRepository(), $this->getId());
    }
}
