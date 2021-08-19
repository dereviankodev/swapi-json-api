<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Pagers\CollectionStrategy;
use App\JsonApi\Relations\GenericRelation;
use App\Models\PeopleRepository;

class PersonAdapter extends AbstractBaseAdapter
{
    protected PeopleRepository $repository;
    protected CollectionStrategy $paging;

    public function __construct(PeopleRepository $repository, CollectionStrategy $paging)
    {
        $this->repository = $repository;
        $this->paging = $paging;
    }

    protected function planet()
    {
        return new GenericRelation();
    }
}
