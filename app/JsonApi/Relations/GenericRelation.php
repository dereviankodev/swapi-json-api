<?php

namespace App\JsonApi\Relations;

use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

class GenericRelation extends AbstractBaseRelation
{
    public function query($record, EncodingParametersInterface $parameters)
    {
        return $record->{$this->field}();
    }
}
