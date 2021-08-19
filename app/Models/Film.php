<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;

/**
 * @method int getId()
 * @method string getTitle()
 * @method void setTitle(string $title)
 * @method string getEpisodeId()
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
 * @method array getCharacters()
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

        return $people;
    }

    public function setCharacters(array $characters): void
    {
        $characterList = [];
        foreach ($characters as $character) {
            if (is_array($character)) {
                $characterList[] = $characters;
                break;
            }

            $urlPath = parse_url($character, PHP_URL_PATH);
            $explodeUrlPath = explode('/', trim($urlPath, '/'));
            $characterData = [
                'type' => $explodeUrlPath[1],
                'id' => $explodeUrlPath[2],
            ];
            $characterList[] = $characterData;
        }

        $this->setAttribute('characters', $characterList);
    }

    public function relationLoaded($arguments): bool
    {
        if (!array_key_exists($arguments, $this->relations)) {
            $peopleRepository = new FilmRepository();
            $id = $this->getId();
            return $this->relationLoad($peopleRepository, $id);
        }

        return true;
    }
}
