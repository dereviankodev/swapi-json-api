<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PeopleRepository extends BaseRepository
{
    protected const RESOURCE_NAME = 'people';

    /**
     * @throws JsonApiException
     */
    public function all($parameters): Collection
    {
        $this->fillParameters($parameters);

        if (empty($this->resource)) {
            $cacheKey = request()->fullUrl();

            if ($this->isCached($cacheKey)) {
                $this->resource = Cache::get($cacheKey);
            } else {
                $this->resource = $this->load();
                $this->putDataIntoCache($cacheKey, $this->resource);
            }
        }

        $data = [
            'results' => [],
            'additional_page_info' => $this->additionalPageInfo()
        ];

        foreach ($this->resource['results'] as $attributes) {
            $data['results'][] = People::create($attributes);
        }

        return collect($data);
    }

    /**
     * @throws JsonApiException
     */
    public function find($resourceId): People
    {
        $cacheKey = request()->fullUrl() . '|' . $resourceId . '|' . static::class;

        if ($this->isCached($cacheKey)) {
            $this->resource = Cache::get($cacheKey);
        } else {
            $this->resource = $this->load($resourceId);
            $this->putDataIntoCache($cacheKey, $this->resource);
        }

        return People::create($this->resource);
    }
}
