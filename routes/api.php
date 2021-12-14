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
