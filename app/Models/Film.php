<?php

namespace App\Models;

/**
 * @method int getId()
 * @method string getTitle()
 * @method void setTitle(string $title)
 * @method int getEpisodeId()
 * @method void setEpisodeId(int $episode_id)
 * @method string getOpeningCrawl()
 * @method void setOpeningCrawl(string $opening_crawl)
 * @method string getDirector()
 * @method void setDirector(string $director)
 * @method string getProducer()
 * @method void setProducer(string $producer)
 * @method string getReleaseDate()
 * @method void setReleaseDate(string $release_date)
 * @method string getCreated()
 * @method void setCreated(string $created)
 * @method string getEdited()
 * @method void setEdited(string $edited)
 * @method string getUrl()
 * @method void setUrl(string $url)
 *
 * @method array getCharacters() Has one
 * @method array getPlanets() Has one
 * @method array getSpecies() Has many
 * @method array getStarships() Has many
 * @method array getVehicles() Has many
 */
class Film extends BaseModel
{
    public static function create(array $attributes): static
    {
        $people = new static();

        $people->setId($attributes['url']);
        $people->setTitle($attributes['title']);
        $people->setEpisodeId($attributes['episode_id']);
        $people->setOpeningCrawl($attributes['opening_crawl']);
        $people->setDirector($attributes['director']);
        $people->setProducer($attributes['producer']);
        $people->setReleaseDate($attributes['release_date']);
        $people->setCreated($attributes['created']);
        $people->setEdited($attributes['edited']);
        $people->setUrl($attributes['url']);

        // Relationship
        $people->setCharacters($attributes['characters']);
        $people->setPlanets($attributes['planets']);
        $people->setSpecies($attributes['species']);
        $people->setStarships($attributes['starships']);
        $people->setVehicles($attributes['vehicles']);

        return $people;
    }

    // Relation Mutators

    public function setCharacters(array $dataList): void
    {
        $this->setAttribute('characters', $this->getParsedDataList($dataList));
    }

    public function setPlanets(array $dataList): void
    {
        $this->setAttribute('planets', $this->getParsedDataList($dataList));
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

    public function people(bool $simple = false): ?array
    {
        $relatedData = $this->getCharacters();
        return $this->getHasMany(People::class, PeopleRepository::class, $relatedData, $simple);
    }

    public function planets(bool $simple = false): object|array|null
    {
        $relatedData = $this->getPlanets();
        return $this->getHasMany(Planet::class, PlanetRepository::class, $relatedData, $simple);
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
        return array_key_exists($arguments, $this->relations) || $this->relationLoad(new FilmRepository(), $this->getId());
    }
}
