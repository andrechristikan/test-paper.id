<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->post('/signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
    $api->post('/login', 'App\\Api\\V1\\Controllers\\LoginController@login');
    
    

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {

        $api->group(['prefix' => 'user'], function(Router $api) {
            $api->get('/profile', 'App\\Api\\V1\\Controllers\\UserController@profile');
        });

        $api->group(['prefix' => 'finance-account'], function(Router $api) {
            $api->get('', 'App\\Api\\V1\\Controllers\\FinanceAccountController@index');
            $api->delete('/destroy/{id}', 'App\\Api\\V1\\Controllers\\FinanceAccountController@destroy');
            $api->put('/update/{id}', 'App\\Api\\V1\\Controllers\\FinanceAccountController@update');
            $api->post('/store', 'App\\Api\\V1\\Controllers\\FinanceAccountController@store');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\FinanceAccountController@show');
        });

        $api->group(['prefix' => 'transaction'], function(Router $api) {
            $api->get('', 'App\\Api\\V1\\Controllers\\TransactionController@index');
            $api->post('/store', 'App\\Api\\V1\\Controllers\\TransactionController@store');
            $api->put('/update/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@update');
            $api->delete('/destroy/{category_type}/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@destroy');
            $api->get('/{category_type}/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@show');
        });

        $api->group(['prefix' => 'report'], function(Router $api) {
            $api->get('', 'App\\Api\\V1\\Controllers\\ReportController@index');
        });

        $api->post('/logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
        $api->post('/refresh', 'App\\Api\\V1\\Controllers\\RefreshController@refresh');

    });

    $api->get('/hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
});
