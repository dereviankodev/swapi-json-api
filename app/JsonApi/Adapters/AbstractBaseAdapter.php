<?php

namespace App\JsonApi\Adapters;

use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Contracts\Pagination\PageInterface;
use CloudCreativity\LaravelJsonApi\Document\Error\Error;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use CloudCreativity\LaravelJsonApi\Exceptions\JsonApiException;
use Illuminate\Support\Collection;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use RuntimeException;

abstract class AbstractBaseAdapter extends AbstractResourceAdapter
{
    protected array $defaultPagination = ['number' => '1'];

    public function query(EncodingParametersInterface $parameters)
    {
        $parameters = $this->getQueryParameters($parameters);
        $transferParameters = $this->getTransferParameters($parameters);
        $records = $this->repository->all($transferParameters);
        $pagination = collect($transferParameters['page']);

        return $pagination->isEmpty()
            ? $records['results']
            : $this->paginate($records, $parameters);
    }

    /**
     * @throws JsonApiException
     */
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

    /**
     * @throws JsonApiException
     */
    public function exists(string $resourceId): bool
    {
        return !is_null($this->find($resourceId));
    }

    public function findMany(iterable $resourceIds): iterable
    {
        throw new RuntimeException('Not implemented');
    }

    protected function createRecord(ResourceObject $resource)
    {
        throw new RuntimeException('Not implemented');
    }

    protected function fillAttributes($record, Collection $attributes)
    {
        throw new RuntimeException('Not implemented');
    }

    protected function persist($record)
    {
        throw new RuntimeException('Not implemented');
    }

    protected function destroy($record)
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * Return the result for a paginated query.
     *
     * @param $collection
     * @param EncodingParametersInterface $parameters
     * @return PageInterface
     */
    protected function paginate($collection, EncodingParametersInterface $parameters): PageInterface
    {
        if (!$this->paging) {
            throw new RuntimeException('Paging is not supported on adapter: ' . get_class($this));
        }

        return $this->paging->paginate($collection, $parameters);
    }

    /**
     * Get JSON API parameters to use when constructing an Eloquent query.
     *
     * This method is used to push in any default parameter values that should
     * be used if the client has not provided any.
     *
     * @param EncodingParametersInterface $parameters
     * @return EncodingParametersInterface
     */
    protected function getQueryParameters(EncodingParametersInterface $parameters): EncodingParametersInterface
    {
        return new EncodingParameters(
            $parameters->getIncludePaths(),
            $parameters->getFieldSets(),
            $parameters->getSortParameters(),
            $parameters->getPaginationParameters() ?: $this->defaultPagination(),
            $parameters->getFilteringParameters(),
            $parameters->getUnrecognizedParameters()
        );
    }

    protected function getTransferParameters(EncodingParametersInterface $parameters): array
    {
        return [
            'page' => $parameters->getPaginationParameters(),
            'filter' => $parameters->getFilteringParameters()
        ];
    }

    private function defaultPagination(): array
    {
        return (array) $this->defaultPagination;
    }
}
