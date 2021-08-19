<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

abstract class AbstractBaseValidators extends AbstractValidators
{
    protected function rules($record, array $data): array
    {
        return [];
    }
}
