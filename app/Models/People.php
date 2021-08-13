<?php

namespace App\Models;

/**
 * @var int $id
 * @var string|null $name
 * @var string|null $birth_year
 * @var string|null $eye_color
 * @var string|null $gender
 * @var string|null $hair_color
 * @var string|null $height
 * @var string|null $mass
 * @var string|null $homeworld
 * @var string|null $created
 * @var string|null $edited
 * @var string|null $url
 */
class People extends BaseModel
{
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
        $people->setHomeworld($attributes['homeworld']);
        $people->setCreated($attributes['created']);
        $people->setEdited($attributes['edited']);
        $people->setUrl($attributes['url']);

        return $people;
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

    public function getBirthYear(): string|null
    {
        return $this->getAttribute('birth_year');
    }

    public function setBirthYear(string $birth_year): void
    {
        $this->setAttribute('birth_year', $birth_year);
    }

    public function getEyeColor(): string|null
    {
        return $this->getAttribute('eye_color');
    }

    public function setEyeColor(string $eye_color): void
    {
        $this->setAttribute('eye_color', $eye_color);
    }

    public function getGender(): string|null
    {
        return $this->getAttribute('gender');
    }

    public function setGender(string $gender): void
    {
        $this->setAttribute('gender', $gender);
    }

    public function getHairColor(): string|null
    {
        return $this->getAttribute('hair_color');
    }

    public function setHairColor(string $hair_color): void
    {
        $this->setAttribute('hair_color', $hair_color);
    }

    public function getHeight(): string|null
    {
        return $this->getAttribute('height');
    }

    public function setHeight(string $height): void
    {
        $this->setAttribute('height', $height);
    }

    public function getMass(): string|null
    {
        return $this->getAttribute('mass');
    }

    public function setMass(string $mass): void
    {
        $this->setAttribute('mass', $mass);
    }

    public function getSkinColor(): string|null
    {
        return $this->getAttribute('skin_color');
    }

    public function setSkinColor(string $skin_color): void
    {
        $this->setAttribute('skin_color', $skin_color);
    }

    public function getHomeworld(): string|null
    {
        return $this->getAttribute('homeworld');
    }

    public function setHomeworld(string $homeworld): void
    {
        $this->setAttribute('homeworld', $homeworld);
    }

    public function getUrl(): string|null
    {
        return $this->getAttribute('url');
    }

    public function setUrl(string $url): void
    {
        $this->setAttribute('url', $url);
    }

    public function getCreated(): string|null
    {
        return $this->getAttribute('created');
    }

    public function setCreated(string $created): void
    {
        $this->setAttribute('created', $created);
    }

    public function getEdited(): string|null
    {
        return $this->getAttribute('edited');
    }

    public function setEdited(string $edited): void
    {
        $this->setAttribute('edited', $edited);
    }
}
