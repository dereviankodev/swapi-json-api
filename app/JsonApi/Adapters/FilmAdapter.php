<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Pagers\CollectionStrategy;
use App\JsonApi\Relations\GenericRelation;
use App\Models\FilmRepository;

class FilmAdapter extends AbstractBaseAdapter
{
    protected FilmRepository $repository;
    protected CollectionStrategy $paging;

    public function __construct(FilmRepository $repository, CollectionStrategy $paging)
    {
        $this->repository = $repository;
        $this->paging = $paging;
    }

    protected function people()
    {
        return new GenericRelation();
    }

    protected function planets()
    {
        return new GenericRelation();
    }

    protected function species()
    {
        return new GenericRelation();
    }

    protected function starships()
    {
        return new GenericRelation();
    }

    protected function vehicles()
    {
        return new GenericRelation();
    }
}
