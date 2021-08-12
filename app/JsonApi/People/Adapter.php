<?php

namespace App\JsonApi\People;

use App\Models\PeopleRepository;
use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Contracts\Pagination\PagingStrategyInterface;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
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
        throw new \RuntimeException('Not implemented');
    }

    public function find(string $resourceId)
    {
        throw new \RuntimeException('Not implemented');
    }

    public function findMany(iterable $resourceIds): iterable
    {
        throw new \RuntimeException('Not implemented');
    }
}
