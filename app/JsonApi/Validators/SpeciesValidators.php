<?php

namespace App\JsonApi\Validators;

class SpeciesValidators extends AbstractBaseValidators
{
    protected $allowedIncludePaths = ['planet', 'people', 'films'];
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
