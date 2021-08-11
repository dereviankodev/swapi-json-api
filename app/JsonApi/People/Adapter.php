<?php

namespace App\JsonApi\People;

use App\Models\PeopleRepository;
use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use Illuminate\Support\Collection;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

class Adapter extends AbstractResourceAdapter
{
    private PeopleRepository $repository;

    public function __construct(PeopleRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function createRecord(ResourceObject $resource)
    {
        // TODO: Implement createRecord() method.
    }

    protected function fillAttributes($record, Collection $attributes)
    {
        // TODO: Implement fillAttributes() method.
    }

    protected function persist($record)
    {
        // TODO: Implement persist() method.
    }

    protected function destroy($record)
    {
        // TODO: Implement destroy() method.
    }

    public function query(EncodingParametersInterface $parameters)
    {
        return $this->repository->all();
    }

    public function exists(string $resourceId): bool
    {
        // TODO: Implement exists() method.
    }

    public function find(string $resourceId)
    {
        // TODO: Implement find() method.
    }

    public function findMany(iterable $resourceIds): iterable
    {
        // TODO: Implement findMany() method.
    }
}
