<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
use CloudCreativity\LaravelJsonApi\Routing\RouteRegistrar as Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

JsonApi::register('v1')->withNamespace('Api')->routes(function (Api $api) {
    // People
    $api->resource('people')/*->controller()->routes(function ($people) {
        $people->get('/', 'index')->name('api.people.index');
        $people->get('{record}', 'show')->name('api.films.show');
        $people->get('schema', 'schema')->name('api.films.schema');
    })*/;
});
