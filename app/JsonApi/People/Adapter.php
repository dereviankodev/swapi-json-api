<?php

namespace App\JsonApi\People;

use App\Models\PeopleRepository;
use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Contracts\Pagination\PagingStrategyInterface;
use CloudCreativity\LaravelJsonApi\Document\Error\Error;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;
use Illuminate\Support\Collection;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

class Adapter extends AbstractResourceAdapter/* implements PagingStrategyInterface*/
{
    private PeopleRepository $repository;

    public function __construct(PeopleRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function createRecord(ResourceObject $resource)
    {
        throw new \RuntimeException('Not implemented');
    }

    protected function fillAttributes($record, Collection $attributes)
    {
        throw new \RuntimeException('Not implemented');
    }

    protected function persist($record)
    {
        throw new \RuntimeException('Not implemented');
    }

    protected function destroy($record)
    {
        throw new \RuntimeException('Not implemented');
    }

    public function query(EncodingParametersInterface $parameters)
    {
        $this->repository->setPaginationParameters($parameters->getPaginationParameters());
        return $this->repository->all();
    }

    public function exists(string $resourceId): bool
    {
        return !is_null($this->find($resourceId));
    }

    public function find(string $resourceId)
    {
        if (!is_numeric($resourceId) || $resourceId < 1) {
            $error = Error::fromArray([
                'status' => 400,
                'title' => 'Bad Request',
                'detail' => 'The identifier must be a numeric and cannot be less than 1'
            ]);

            throw new JsonApiException($error);
        }

        return $this->repository->find($resourceId);
    }

    public function findMany(iterable $resourceIds): iterable
    {
        throw new \RuntimeException('Not implemented');
    }
}
