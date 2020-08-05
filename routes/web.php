<?php

use Illuminate\Support\Facades\Route;

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


$router->get('/', 'NimbusController@hello');
$router->get('hello', 'NimbusController@hello');

$router->any('login', 'NimbusController@login');
$router->any('list', 'NimbusController@list');
$router->any('get', 'NimbusController@get');
$router->any('store', 'NimbusController@store');
$router->any('logout', 'NimbusController@logout');
