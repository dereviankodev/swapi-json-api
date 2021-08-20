<?php

namespace App\Models;

/**
 * @method int getId()
 * @method string getName()
 * @method void setName(string $name)
 * @method string getClassification()
 * @method void setClassification(string $classification)
 * @method string getDesignation()
 * @method void setDesignation(string $designation)
 * @method string getAverageHeight()
 * @method void setAverageHeight(string $average_height)
 * @method string getAverageLifespan()
 * @method void setAverageLifespan(string $average_lifespan)
 * @method string getEyeColors()
 * @method void setEyeColors(string $eye_colors)
 * @method string getHairColors()
 * @method void setHairColors(string $hair_colors)
 * @method string getSkinColors()
 * @method void setSkinColors(string $skin_colors)
 * @method string getLanguage()
 * @method void setLanguage(string $language)
 * @method string getCreated()
 * @method void setCreated(string $created)
 * @method string getEdited()
 * @method void setEdited(string $edited)
 * @method string getUrl()
 * @method void setUrl(string $url)
 *
 * @method array getHomeworld() Has one
 * @method array getPeople() Has many
 * @method array getFilms() Has many
 */
class Species extends BaseModel
{
    public static function create(array $attributes): static
    {
        $people = new static();

        $people->setId($attributes['url']);
        $people->setName($attributes['name']);
        $people->setClassification($attributes['classification']);
        $people->setDesignation($attributes['designation']);
        $people->setAverageHeight($attributes['average_height']);
        $people->setAverageLifespan($attributes['average_lifespan']);
        $people->setEyeColors($attributes['eye_colors']);
        $people->setHairColors($attributes['hair_colors']);
        $people->setSkinColors($attributes['skin_colors']);
        $people->setLanguage($attributes['language']);
        $people->setCreated($attributes['created']);
        $people->setEdited($attributes['edited']);
        $people->setUrl($attributes['url']);

        // Relationship
        $people->setHomeworld($attributes['homeworld']);
        $people->setPeople($attributes['people']);
        $people->setFilms($attributes['films']);

        return $people;
    }

    // Relation Mutators

    public function setHomeworld(mixed $data): void
    {
        $this->setAttribute('homeworld', $this->getParsedData($data));
    }

    public function setPeople(array $dataList): void
    {
        $this->setAttribute('people', $this->getParsedDataList($dataList));
    }

    public function setFilms(array $dataList): void
    {
        $this->setAttribute('films', $this->getParsedDataList($dataList));
    }

    // Relationship data

    public function planet(bool $simple = false): object|array|null
    {
        $relatedData = $this->getHomeworld();
        return $this->getHasOne(Planet::class, PlanetRepository::class, $relatedData, $simple);
    }

    public function people(bool $simple = false): object|array|null
    {
        $relatedData = $this->getPeople();
        return $this->getHasMany(People::class, PeopleRepository::class, $relatedData, $simple);
    }

    public function films(bool $simple = false): ?array
    {
        $relatedData = $this->getFilms();
        return $this->getHasMany(Film::class, FilmRepository::class, $relatedData, $simple);
    }

    public function relationLoaded($arguments): bool
    {
        return array_key_exists($arguments, $this->relations) || $this->relationLoad(new SpeciesRepository(), $this->getId());
    }
}
