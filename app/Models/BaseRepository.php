<?php

namespace App\Models;

use App\Services\Cache\CacheService;
use CloudCreativity\LaravelJsonApi\Document\Error\Error;
use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BaseRepository
{
    use CacheService;

    protected const BASE_URL_API = 'https://swapi.dev/api';

    protected array $resource = [];
    private array $pagingParameters = [];
    private array $filteringParameters = [];

    public function additionalPageInfo(): array
    {
        return [
            'total' => $this->resource['count']
        ];
    }

    /**
     * @throws JsonApiException
     */
    protected function getAll($className, $parameters): array
    {
        $this->fillParameters($parameters);

        if (empty($this->resource)) {
            $cacheKey = request()->fullUrl();
            $this->getDataIntoResource($cacheKey);
        }

        $data = [
            'results' => null,
            'additional_page_info' => $this->additionalPageInfo()
        ];

        foreach ($this->resource['results'] as $attributes) {
            $data['results'][] = $className::create($attributes);
        }

        return $data;
    }

    /**
     * @throws JsonApiException
     */
    protected function getOne($className, $resourceId)
    {
        $cacheKey = request()->fullUrl() . '|' . $resourceId . '|' . $className;
        $this->getDataIntoResource($cacheKey, $resourceId);

        return $className::create($this->resource);
    }

    /**
     * @throws JsonApiException
     */
    protected function load(int $resourceId = null)
    {
        $uri = $this->getUriApi($resourceId);
        $query = $this->getQueryApi();
        $data = Http::get($uri, $query);

        if ($data->clientError()) {
            $error = Error::fromArray([
                'status' => $data->status(),
                'title' => $data->object()->detail ?? 'Unknown error',
            ]);

            throw new JsonApiException($error);
        }

        return json_decode($data, true);
    }

    protected function fillParameters($parameters)
    {
        $this->setPagingParameters($parameters['page']);
        $this->setFilteringParameters($parameters['filter']);
    }

    /**
     * @throws JsonApiException
     */
    private function getDataIntoResource($cacheKey, int $resourceId = null): void
    {
        if ($this->isCached($cacheKey)) {
            $this->resource = Cache::get($cacheKey);
        } else {
            $this->resource = $this->load($resourceId);
            $this->putDataIntoCache($cacheKey, $this->resource);
        }
    }

    private function getPagingNumber(): ?string
    {
        if (isset($this->pagingParameters['number'])) {
            return $this->pagingParameters['number'];
        }

        return null;
    }

    private function setPagingParameters($pagingParameters): void
    {
        $this->pagingParameters = $pagingParameters ?? [];
    }

    private function getFilteringValue(): ?string
    {
        if (isset($this->filteringParameters)) {
            return reset($this->filteringParameters);
        }

        return null;
    }

    private function setFilteringParameters($filteringParameters): void
    {
        $this->filteringParameters = $filteringParameters ?? [];
    }

    private function getUriApi(int $id = null): string
    {
        $urlApiArr = [
            static::BASE_URL_API,
            static::RESOURCE_NAME,
            $id
        ];

        return implode('/', $urlApiArr);
    }

    private function getQueryApi(): array
    {
        return [
            'search' => $this->getFilteringValue(),
            'page' => $this->getPagingNumber()
        ];
    }
}
