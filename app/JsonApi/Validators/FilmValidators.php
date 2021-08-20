<?php

namespace App\JsonApi\Validators;

class FilmValidators extends AbstractBaseValidators
{
    protected $allowedIncludePaths = ['people', 'planets', 'species', 'starships', 'vehicles'];
    protected $allowedPagingParameters = ['number', 'size'];
    protected $allowedFilteringParameters = ['title'];
    protected $allowedSortParameters = [];

    protected function queryRules(): array
    {
        return [
            'page.number' => 'filled|numeric|min:1,',
            'page.size' => 'filled|numeric|',
            'filter.title' => 'filled|string'
        ];
    }
}
