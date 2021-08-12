<?php

namespace App\JsonApi\People;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    protected $allowedSortParameters = [];

    protected $allowedPagingParameters = ['number'];

    /**
     * Get the validation rules for the resource.
     *
     * @param $record
     *      the record being updated, or null if it is a create request.
     * @return array
     */
    protected function rules($record, array $data): array
    {
        return [
            //
        ];
    }

    /**
     * @return array
     */
    protected function queryRules(): array
    {
        return [
            'page.number' => 'numeric|min:1,'
        ];
    }

}
