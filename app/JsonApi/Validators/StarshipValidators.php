<?php

namespace App\JsonApi\Validators;

class StarshipValidators extends AbstractBaseValidators
{
    protected $allowedIncludePaths = ['people', 'films'];
    protected $allowedPagingParameters = ['number', 'size'];
    protected $allowedFilteringParameters = ['name', 'model'];
    protected $allowedSortParameters = [];

    protected function queryRules(): array
    {
        return [
            'page.number' => 'filled|numeric|min:1,',
            'page.size' => 'filled|numeric|',
            'filter.name' => 'filled|string',
            'filter.model' => 'filled|string',
        ];
    }
}
