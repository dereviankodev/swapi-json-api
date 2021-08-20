<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Pagers\CollectionStrategy;
use App\JsonApi\Relations\GenericRelation;
use App\Models\StarshipRepository;

class StarshipAdapter extends AbstractBaseAdapter
{
    protected StarshipRepository $repository;
    protected CollectionStrategy $paging;

    public function __construct(StarshipRepository $repository, CollectionStrategy $paging)
    {
        $this->repository = $repository;
        $this->paging = $paging;
    }

    protected function people()
    {
        return new GenericRelation();
    }

    protected function films()
    {
        return new GenericRelation();
    }
}
