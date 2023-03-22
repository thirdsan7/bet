<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Middleware\DBTransaction;
use App\Http\Middleware\EyeconDBTransaction;
use App\Http\Middleware\FunkyDBTransaction;

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

$router->group(['middleware' => DBTransaction::class], function () use ($router) {

    $router->post('sellbet', ['uses' => 'ZirconController@sellBet']);
    $router->get('api/eyecon', ['uses' => 'EyeconController@entry']);
    $router->post('Funky/Bet/PlaceBet', ['uses' => 'FunkyController@placeBet']);
});
