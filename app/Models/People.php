<?php

namespace App\Models;

class People
{
    private ?string $name = null;
    private ?string $birth_year = null;
    private ?string $eye_color = null;
    private ?string $gender = null;
    private ?string $hair_color = null;
    private ?string $height = null;
    private ?string $mass = null;
    private ?string $homeworld = null;
    private ?string $created = null;
    private ?string $edited = null;
    private ?string $self = null;

    public static function create(array $attributes): self
    {
        $people = new self();

        $people->setName($attributes['name']);
        $people->setBirthYear($attributes['birth_year']);
        $people->setEyeColor($attributes['eye_color']);
        $people->setGender($attributes['gender']);
        $people->setHairColor($attributes['hair_color']);
        $people->setHeight($attributes['height']);
        $people->setMass($attributes['mass']);
        $people->setHomeworld($attributes['homeworld']);
        $people->setCreated($attributes['created']);
        $people->setEdited($attributes['edited']);
        $people->setSelf($attributes['url']);

        return $people;
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

    public function getHomeworld(): string
    {
        return $this->homeworld;
    }

    public function setHomeworld(string $homeworld): void
    {
        $this->homeworld = $homeworld;
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

    public function getSelf(): string
    {
        return $this->self;
    }

    public function setSelf(string $self): void
    {
        $this->self = $self;
    }
}
