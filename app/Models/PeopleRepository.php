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

    private const RESOURCE_NAME = 'people';

    private ?array $people = null;
    private ?array $paginationParameters = null;

    /**
     * @throws JsonApiException
     */
    public function all(): Generator
    {
        if (is_null($this->people)) {
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

    /**
     * @throws JsonApiException
     */
    public function find($resourceId): People
    {
        $cacheKey = request()->fullUrl();

        if ($this->isCached($cacheKey)) {
            $data = Cache::get($cacheKey);
            return People::create($data);
        }

        $data = $this->load($resourceId);
        $this->people = $this->putDataIntoCache($cacheKey, $data);

        return People::create($data);
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
        $data = Http::get('https://swapi.dev/api/people/' . $resourceId, [
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
