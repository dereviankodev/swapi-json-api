<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class PersonValidators extends AbstractValidators
{
    protected $allowedIncludePaths = ['planet'];
    protected $allowedPagingParameters = ['number', 'size'];
    protected $allowedFilteringParameters = ['name'];
    protected $allowedSortParameters = [];

    protected function rules($record, array $data): array
    {
        return [];
    }

    protected function queryRules(): array
    {
        return [
            'page.number' => 'filled|numeric|min:1,',
            'page.size' => 'filled|numeric|',
            'filter.name' => 'filled|string'
        ];
    }
}
