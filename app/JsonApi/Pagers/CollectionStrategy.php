<?php

namespace App\JsonApi\Pagers;

use CloudCreativity\LaravelJsonApi\Contracts\Pagination\PageInterface;
use CloudCreativity\LaravelJsonApi\Contracts\Pagination\PagingStrategyInterface;
use CloudCreativity\LaravelJsonApi\Pagination\CreatesPages;
use Illuminate\Pagination\LengthAwarePaginator;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Contracts\Http\Query\QueryParametersParserInterface;

class CollectionStrategy implements PagingStrategyInterface
{
    use CreatesPages;

    protected ?string $metaKey;

    /**
     * StandardStrategy constructor.
     */
    public function __construct()
    {
        $this->metaKey = QueryParametersParserInterface::PARAM_PAGE;
    }

    public function paginate($query, EncodingParametersInterface $parameters): PageInterface
    {
        $paginator = new LengthAwarePaginator(
            $query['results'],
            $query['additional_page_info']['total'],
            10,
            $parameters->getPaginationParameters()['number']
        );

        return $this->createPage($paginator, $parameters);
    }

}
