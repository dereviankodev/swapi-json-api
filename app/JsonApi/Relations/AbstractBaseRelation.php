<?php

namespace App\JsonApi\Relations;

use CloudCreativity\LaravelJsonApi\Adapter\AbstractRelationshipAdapter;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use RuntimeException;

abstract class AbstractBaseRelation extends AbstractRelationshipAdapter
{
    public function update($record, array $relationship, EncodingParametersInterface $parameters)
    {
        throw new RuntimeException('Not implemented');
    }

    public function replace($record, array $relationship, EncodingParametersInterface $parameters)
    {
        throw new RuntimeException('Not implemented');
    }
}
