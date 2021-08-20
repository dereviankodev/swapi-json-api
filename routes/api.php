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

JsonApi::register('v1')->routes(function (Api $api) {
    // People
    $api->resource('people')->relationships(function ($relations) {
        $relations->hasOne('planet');
        $relations->hasMany('films');
        $relations->hasMany('species');
        $relations->hasMany('starships');
        $relations->hasMany('vehicles');
    });

    // Planets
    $api->resource('planets')->relationships(function ($relations) {
        $relations->hasMany('people');
        $relations->hasMany('films');
    });

    // Films
    $api->resource('films')->relationships(function ($relations) {
        $relations->hasMany('people');
        $relations->hasMany('planet');
        $relations->hasMany('species');
        $relations->hasMany('starships');
        $relations->hasMany('vehicles');
    });

    // Species
    $api->resource('species')->relationships(function ($relations) {
        $relations->hasOne('planet');
        $relations->hasMany('people');
        $relations->hasMany('films');
    });

    // Starships
    $api->resource('starships')->relationships(function ($relations) {
        $relations->hasMany('people');
        $relations->hasMany('films');
    });
});
