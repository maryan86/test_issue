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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('user/sign-in', 'UserController@signin');
    $router->post('user/register', 'UserController@register');
    $router->post('user/recover-password', 'PasswordController@sendResetLinkEmail');
    $router->patch('user/recover-password', 'PasswordController@reset');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('users', 'UserController@index');
        $router->get('user/companies', 'UserController@companies');
        $router->post('user/companies', 'CompanyController@store');
    });
});
