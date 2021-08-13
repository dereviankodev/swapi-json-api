<?php

namespace App\Models;

use App\Services\Cache\CacheService;
use CloudCreativity\LaravelJsonApi\Document\Error\Error;
use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;
use Generator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PeopleRepository
{
    use CacheService;

    private ?array $people = null;
    private ?array $paginationParameters = null;

    public function all(): Generator
    {
        if (!is_array($this->people)) {
            $cacheKey = request()->fullUrl();

            if ($this->isCached($cacheKey)) {
                $this->people = Cache::get($cacheKey);
            } else {
                $data = $this->load();
                $this->people = $this->putDataIntoCache($cacheKey, $data);
            }
        }

        foreach ($this->people['results'] as $attributes) {
            yield People::create($attributes);
        }
    }

    public function setPaginationParameters(array|null $paginationParameters): void
    {
        $this->paginationParameters = $paginationParameters;
    }

    public function getPaginationParameters(): array
    {
        return $this->paginationParameters;
    }

    /**
     * @throws JsonApiException
     */
    private function load()
    {
        $data = Http::get('https://swapi.dev/api/people/', [
            'page' => $this->getPaginationNumber()
        ]);

        if ($data->clientError()) {
            $error = Error::fromArray([
                'status' => $data->status(),
                'title' => $data->object()->detail,
            ]);

            throw new JsonApiException($error);
        }

        return json_decode($data, true);
    }

    private function getPaginationNumber()
    {
        if (
            is_array($this->paginationParameters)
            && isset($this->paginationParameters['number'])
        ) {
            return $this->paginationParameters['number'];
        }

        return null;
    }

    private function isCached($cacheKey): bool
    {
        if (Cache::has($cacheKey)) {
            return true;
        }

        return false;
    }
}
