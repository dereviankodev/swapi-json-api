<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Pagers\CollectionStrategy;
use App\JsonApi\Relations\GenericRelation;
use App\Models\VehicleRepository;

class VehicleAdapter extends AbstractBaseAdapter
{
    protected VehicleRepository $repository;
    protected CollectionStrategy $paging;

    public function __construct(VehicleRepository $repository, CollectionStrategy $paging)
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
