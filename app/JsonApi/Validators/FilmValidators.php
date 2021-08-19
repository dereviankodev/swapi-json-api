<?php

namespace App\JsonApi\Validators;

class FilmValidators extends AbstractBaseValidators
{
    protected $allowedIncludePaths = ['people'];
    protected $allowedPagingParameters = ['number', 'size'];
    protected $allowedFilteringParameters = ['name'];
    protected $allowedSortParameters = [];

    protected function queryRules(): array
    {
        return [
            'page.number' => 'filled|numeric|min:1,',
            'page.size' => 'filled|numeric|',
            'filter.name' => 'filled|string'
        ];
    }
}
