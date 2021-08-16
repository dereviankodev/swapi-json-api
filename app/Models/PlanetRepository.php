<?php

namespace App\Models;

use App\Services\Cache\CacheService;
use CloudCreativity\LaravelJsonApi\Document\Error\Error;
use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;
use Generator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PlanetRepository
{
    use CacheService;

    private const RESOURCE_NAME = 'planets';

    private ?array $planets = null;
    private ?array $paginationParameters = null;

    /**
     * @throws JsonApiException
     */
    public function all(): Generator
    {
        if (is_null($this->planets)) {
            $cacheKey = request()->fullUrl();

            if ($this->isCached($cacheKey)) {
                $this->planets = Cache::get($cacheKey);
            } else {
                $data = $this->load();
                $this->planets = $this->putDataIntoCache($cacheKey, $data);
            }
        }

        foreach ($this->planets['results'] as $attributes) {
            yield Planet::create($attributes);
        }
    }

    /**
     * @throws JsonApiException
     */
    public function find($resourceId): Planet
    {
        $cacheKey = request()->fullUrl();

        if ($this->isCached($cacheKey)) {
            $data = Cache::get($cacheKey);
            return Planet::create($data);
        }

        $data = $this->load($resourceId);
        $this->planets = $this->putDataIntoCache($cacheKey, $data);

        return Planet::create($data);
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
    private function load(int $resourceId = null)
    {
        $data = Http::get('https://swapi.dev/api/planets/' . $resourceId, [
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
