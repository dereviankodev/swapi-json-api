<?php

namespace App\Models;

class People
{
    /**
     * Unique identifier
     */
    private ?int $id = null;

    /**
     * The name of this person.
     */
    private ?string $name = null;

    /**
     * The birth year of the person, using the in-universe standard
     * of BBY or ABY - Before the Battle of Yavin or After the Battle of Yavin.
     * The Battle of Yavin is a battle that occurs at the end of Star Wars episode IV: A New Hope.
     */
    private ?string $birth_year = null;

    /**
     * The eye color of this person.
     * Will be "unknown" if not known or "n/a" if the person does not have an eye.
     */
    private ?string $eye_color = null;

    /**
     * The gender of this person.
     * Either "Male", "Female" or "unknown", "n/a" if the person does not have a gender.
     */
    private ?string $gender = null;

    /**
     * The hair color of this person.
     * Will be "unknown" if not known or "n/a" if the person does not have hair.
     */
    private ?string $hair_color = null;

    /**
     * The height of the person in centimeters.
     */
    private ?string $height = null;

    /**
     * The mass of the person in kilograms.
     */
    private ?string $mass = null;

    /**
     * The skin color of this person.
     */
    private ?string $skin_color = null;

    /**
     * The URL of a planet resource,
     * a planet that this person was born on or inhabits.
     */
    private ?string $homeworld = null;

    /**
     * The ISO 8601 date format of the time that this resource was created.
     */
    private ?string $created = null;

    /**
     * The ISO 8601 date format of the time that this resource was edited.
     */
    private ?string $edited = null;

    /**
     * The hypermedia URL of this resource.
     */
    private ?string $url = null;

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

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function setId(string $url): void
    {
        $this->id = basename($url);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBirthYear(): string
    {
        return $this->birth_year;
    }

    public function setBirthYear(string $birth_year): void
    {
        $this->birth_year = $birth_year;
    }

    public function getEyeColor(): string
    {
        return $this->eye_color;
    }

    public function setEyeColor(string $eye_color): void
    {
        $this->eye_color = $eye_color;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getHairColor(): string
    {
        return $this->hair_color;
    }

    public function setHairColor(string $hair_color): void
    {
        $this->hair_color = $hair_color;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    public function setHeight(string $height): void
    {
        $this->height = $height;
    }

    public function getMass(): string
    {
        return $this->mass;
    }

    public function setMass(string $mass): void
    {
        $this->mass = $mass;
    }

    public function getSkinColor(): string
    {
        return $this->skin_color;
    }

    public function setSkinColor(string $skin_color): void
    {
        $this->skin_color = $skin_color;
    }

    public function getHomeworld(): string
    {
        return $this->homeworld;
    }

    public function setHomeworld(string $homeworld): void
    {
        $this->homeworld = $homeworld;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function setCreated(string $created): void
    {
        $this->created = $created;
    }

    public function getEdited(): string
    {
        return $this->edited;
    }

    public function setEdited(string $edited): void
    {
        $this->edited = $edited;
    }
}
