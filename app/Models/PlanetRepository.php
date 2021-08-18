<?php

namespace App\Models;

use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PlanetRepository extends BaseRepository
{
    protected const RESOURCE_NAME = 'planets';

    /**
     * @throws JsonApiException
     */
    public function all($parameters): Collection
    {
        if (is_array($parameters)) {
            $this->setPaginationParameters($parameters);
        }

        if (empty($this->resource)) {
            $cacheKey = request()->fullUrl();

            if ($this->isCached($cacheKey)) {
                $this->resource = Cache::get($cacheKey);
            } else {
                $this->resource = $this->load();
                $this->putDataIntoCache($cacheKey, $this->resource);
            }
        }

        $data = [];

        foreach ($this->resource['results'] as $attributes) {
            $data['results'][] = Planet::create($attributes);
        }

        $data['additional_page_info'] = $this->additionalPageInfo();

        return collect($data);
    }

    /**
     * @throws JsonApiException
     */
    public function find($resourceId): Planet
    {
        $cacheKey = request()->fullUrl() . '|' . $resourceId . '|' . static::class;

        if ($this->isCached($cacheKey)) {
            $this->resource = Cache::get($cacheKey);
        } else {
            $this->resource = $this->load($resourceId);
            $this->putDataIntoCache($cacheKey, $this->resource);
        }

        return Planet::create($this->resource);
    }
}
