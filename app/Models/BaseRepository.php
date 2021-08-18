<?php

namespace App\Models;

use App\Services\Cache\CacheService;
use CloudCreativity\LaravelJsonApi\Document\Error\Error;
use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;
use Illuminate\Support\Facades\Http;

class BaseRepository
{
    use CacheService;

    protected const BASE_URL_API = 'https://swapi.dev/api';

    protected array $resource = [];
    private array $paginationParameters = [];

    public function additionalPageInfo(): array
    {
        return [
            'total' => $this->resource['count']
        ];
    }

    protected function setPaginationParameters($paginationParameters): void
    {
        $this->paginationParameters = $paginationParameters ?? [];
    }

    protected function getPaginationNumber()
    {
        if (isset($this->paginationParameters['number'])) {
            return $this->paginationParameters['number'];
        }

        return null;
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
            'page' => $this->getPaginationNumber()
        ];
    }
}
