<?php

use Src\Router\Route;

Route::get([
    'set' => '/',
    'as' => 'home'
], 'HomeController@index');

Route::get([
    'set' => '/login',
    'as' => 'login'
], 'AuthController@index');
Route::post([
    'set' => '/login',
    'as' => 'login'
], 'AuthController@login');

Route::get([
    'set' => '/register',
    'as' => 'register'
], 'AuthController@register');
Route::post([
    'set' => '/register',
    'as' => 'register'
], 'AuthController@save');

Route::get([
    'set' => '/profile',
    'as' => 'profile'
], 'AuthController@profile');
Route::post([
    'set' => '/profile',
    'as' => 'profile'
], 'AuthController@update');

Route::get([
    'set' => '/logout',
    'as' => 'logout'
], 'AuthController@logout');


/**
 *
 * ADMIN ROUTES
 *
 */

Route::get([
    'set' => '/admin/login',
    'as' => 'admin.login',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'AdminController@index');

Route::post([
    'set' => '/admin/login',
    'as' => 'admin.login',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'AdminController@login');

Route::get([
    'set' => '/admin/home',
    'as' => 'admin.home',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'HomeController@index');

Route::get([
    'set' => '/admin/profile',
    'as' => 'admin.profile',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'AdminController@profile');

Route::post([
    'set' => '/admin/profile',
    'as' => 'admin.profile',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'AdminController@update');

Route::get([
    'set' => '/admin/users',
    'as' => 'admin.users.index',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'UsersController@index');

Route::get([
    'set' => '/admin/users/create',
    'as' => 'admin.users.create',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'UsersController@create');

Route::post([
    'set' => '/admin/users/store',
    'as' => 'admin.users.store',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'UsersController@store');

Route::get([
    'set' => '/admin/users/edit/{id}',
    'as' => 'admin.users.edit',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'UsersController@edit');

Route::post([
    'set' => '/admin/users/{id}/update',
    'as' => 'admin.users.update',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'UsersController@update');

Route::get([
    'set' => '/admin/users/{id}/destroy',
    'as' => 'admin.users.destroy',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'UsersController@destroy');

Route::get([
    'set' => '/admin/logout',
    'as' => 'admin.logout',
    'namespace' => "Src\\Controllers\\Admin\\"
], 'AdminController@logout');