<?php

namespace App\JsonApi\People;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    protected $allowedIncludePaths = ['planet'];
    protected $allowedPagingParameters = ['number'];
    protected $allowedSortParameters = [];

    protected function rules($record, array $data): array
    {
        return [];
    }

    protected function queryRules(): array
    {
        return [
            'page.number' => 'numeric|min:1,'
        ];
    }
}
