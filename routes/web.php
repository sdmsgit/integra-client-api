<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->group(['prefix' => '/token'], function () use ($router) {
    $router->post('/', [
        'uses' => 'TokenController@generate'
    ]);
});

$router->group(['prefix' => '/input-setup'], function () use ($router) {
    $router->get('/', [
        'uses' => 'InputSetupController@index'
    ]);
});
$router->group(['prefix' => '/entity'], function () use ($router) {
    $router->get('/', [
        'uses' => 'EntityController@index'
    ]);
});

$router->group(['prefix' => '/uom'], function () use ($router) {
    $router->get('/', [
        'uses' => 'UomController@index'
    ]);
});

$router->group(['prefix' => '/indikator'], function () use ($router) {
    $router->get('/', [
        'uses' => 'IndikatorController@index'
    ]);
});

$router->group(['prefix' => '/detail-indikator'], function () use ($router) {
    $router->get('/', [
        'uses' => 'DetailIndikatorController@index'
    ]);
});
$router->group(['prefix' => '/input'], function () use ($router) {
    $router->post('/', [
        'uses' => 'InputController@store'
    ]);
});

$router->post('{any:.*}', [
    'uses' => 'IndexController@index'
]);
$router->put('{any:.*}', [
    'uses' => 'IndexController@index'
]);
$router->delete('{any:.*}', [
    'uses' => 'IndexController@index'
]);
$router->get('{any:.*}', [
    'uses' => 'IndexController@index'
]);
