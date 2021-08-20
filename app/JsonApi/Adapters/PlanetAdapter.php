<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Pagers\CollectionStrategy;
use App\JsonApi\Relations\GenericRelation;
use App\Models\PlanetRepository;

class PlanetAdapter extends AbstractBaseAdapter
{
    protected PlanetRepository $repository;
    protected CollectionStrategy $paging;

    public function __construct(PlanetRepository $repository, CollectionStrategy $paging)
    {
        $this->repository = $repository;
        $this->paging = $paging;
    }

    protected function people(): GenericRelation
    {
        return new GenericRelation();
    }

    protected function films(): GenericRelation
    {
        return new GenericRelation();
    }
}
