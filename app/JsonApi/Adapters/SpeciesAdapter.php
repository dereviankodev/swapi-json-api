<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Pagers\CollectionStrategy;
use App\JsonApi\Relations\GenericRelation;
use App\Models\SpeciesRepository;

class SpeciesAdapter extends AbstractBaseAdapter
{
    protected SpeciesRepository $repository;
    protected CollectionStrategy $paging;

    public function __construct(SpeciesRepository $repository, CollectionStrategy $paging)
    {
        $this->repository = $repository;
        $this->paging = $paging;
    }

    protected function planet(): GenericRelation
    {
        return new GenericRelation();
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
