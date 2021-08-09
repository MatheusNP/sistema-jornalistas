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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', ['uses' => 'AuthController@register']);
    $router->post('login', ['uses' => 'AuthController@login']);
    $router->post('me', ['uses' => 'AuthController@me']);

    $router->group(['middleware' => 'auth', 'prefix' => 'type'], function () use ($router) {
        $router->post('create', ['uses' => 'TypeController@create']);
        $router->post('update/{type_id}', ['uses' => 'TypeController@update']);
        $router->post('delete/{type_id}', ['uses' => 'TypeController@delete']);
        $router->get('me',  ['uses' => 'TypeController@allByMe']);
    });

    $router->group(['middleware' => 'auth', 'prefix' => 'news'], function () use ($router) {
        $router->post('create', ['uses' => 'NewsController@create']);
        $router->post('update/{news_id}', ['uses' => 'NewsController@update']);
        $router->post('delete/{news_id}', ['uses' => 'NewsController@delete']);
        $router->get('me',  ['uses' => 'NewsController@allByMe']);
        $router->get('type/{type_id}', ['uses' => 'NewsController@allByMeByType']);
    });



    // RESTful
    $router->group(['prefix' => 'v1'], function () use ($router) {
        $router->post('register', ['uses' => 'AuthController@register']);
        $router->post('login', ['uses' => 'AuthController@login']);
        $router->get('me', ['uses' => 'AuthController@me']);

        $router->group(['middleware' => 'auth', 'prefix' => 'type'], function () use ($router) {
            $router->post('/', ['uses' => 'TypeController@create']);
            $router->put('/{type_id}', ['uses' => 'TypeController@update']);
            $router->delete('/{type_id}', ['uses' => 'TypeController@delete']);
            $router->get('/me',  ['uses' => 'TypeController@allByMe']);
        });

        $router->group(['middleware' => 'auth', 'prefix' => 'news'], function () use ($router) {
            $router->post('/', ['uses' => 'NewsController@create']);
            $router->put('/{news_id}', ['uses' => 'NewsController@update']);
            $router->delete('/{news_id}', ['uses' => 'NewsController@delete']);
            $router->get('/me',  ['uses' => 'NewsController@allByMe']);
            $router->get('/me/type/{type_id}', ['uses' => 'NewsController@allByMeByType']);
        });
    });
});





