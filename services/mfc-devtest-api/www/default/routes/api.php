<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function (Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');

        $api->post('logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
        $api->post('refresh', 'App\\Api\\V1\\Controllers\\RefreshController@refresh');
        $api->get('me', 'App\\Api\\V1\\Controllers\\UserController@me');
    });

    $api->group(['middleware' => 'jwt.auth'], function (Router $api) {
        $api->get('protected', function () {
            return response()->json([
                'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.',
            ]);
        });

        $api->get('companies', 'App\Api\V1\Controllers\CompanyController@index');
        $api->get('companies/{id}', 'App\Api\V1\Controllers\CompanyController@show');
        $api->post('companies', 'App\Api\V1\Controllers\CompanyController@store');
        $api->put('companies/{id}', 'App\Api\V1\Controllers\CompanyController@update');
        $api->delete('companies/{id}', 'App\Api\V1\Controllers\CompanyController@destroy');

        $api->get('employees', 'App\Api\V1\Controllers\EmployeeController@index');
        $api->get('employees/{id}', 'App\Api\V1\Controllers\EmployeeController@show');
        $api->post('employees', 'App\Api\V1\Controllers\EmployeeController@store');
        $api->put('employees/{id}', 'App\Api\V1\Controllers\EmployeeController@update');
        $api->delete('employees/{id}', 'App\Api\V1\Controllers\EmployeeController@destroy');

        $api->get('users', 'App\Api\V1\Controllers\UserController@index');
        $api->get('users/{id}', 'App\Api\V1\Controllers\UserController@show');
        $api->post('users', 'App\Api\V1\Controllers\UserController@store');
        $api->put('users/{id}', 'App\Api\V1\Controllers\UserController@update');
        $api->delete('users/{id}', 'App\Api\V1\Controllers\UserController@destroy');

        $api->post('upload', 'App\Api\V1\Controllers\UploadController@store');
        $api->delete('upload/{filename}', 'App\Api\V1\Controllers\UploadController@destroy');

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function () {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!',
                ]);
            },
        ]);
    });

    $api->get('hello', function () {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.',
        ]);
    });
});
