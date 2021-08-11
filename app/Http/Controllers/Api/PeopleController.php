<?php

namespace App\Http\Controllers\Api;

use CloudCreativity\LaravelJsonApi\Contracts\Store\StoreInterface;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use CloudCreativity\LaravelJsonApi\Http\Requests\FetchResources;

class PeopleController extends JsonApiController
{
    public function index(StoreInterface $store, FetchResources $request)
    {

    }
}
