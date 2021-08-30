<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    dd(array_keys(config('json-api-v1.resources')));
//    dd(json_api()->links()->index('people'));
//    echo json_api()->url()->index('people', ['page[number]' => '2']);
//    echo json_api()->url()->read('people', 1, ['include' => 'planet,films']);
//    echo json_api()->url()->readRelationship('people', 1, 'planet');
//    var_dump(parse_url('http://swapi-json-api.loc/api/v1/people?page%5Bnumber%5D=1&page%5Bsize%5D=10'));
//    var_dump(basename('http://swapi-json-api.loc/api/v1/people?page%5Bnumber%5D=1&page%5Bsize%5D=10'));
//    var_dump(parse_url(basename('http://swapi-json-api.loc/api/v1/people?page%5Bnumber%5D=1&page%5Bsize%5D=10')));
//    echo $url = urldecode('http://swapi-json-api.loc/api/v1/people?page%5Bnumber%5D=1&page%5Bsize%5D=10');
//    $parseUrl = parse_url(basename($url));
//    $query = [];
//    parse_str($parseUrl['query'], $query);
//    var_dump($parseUrl['path'], $query);



    $people = "people/";
    $parseUrl = parse_url($people);
    $path = trim($parseUrl['path'], '/');
    $query = $parseUrl['query'] ?? null;
    if (!is_numeric(basename($path)) && isset($parseUrl['query'])) {
//        $query = [];
        parse_str($parseUrl['query'], $query);
        echo json_api()->url()->index($path, $query);
    } else {
        $pathArr = explode('/', $path);
        echo json_api()->url()->read($pathArr[0], $pathArr[1]);
    }

    $entityName = Str::of('PeopleHandler')->before('Handler')->plural()->lower();
    $qwe = Str::of($people)->startsWith($entityName);

//    var_dump(Str::before('PeopleHandler', 'Handler'));

//    var_dump($parseUrl);



//    return view('welcome');
});
