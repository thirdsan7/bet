<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\FunkyAuthenticationToken;

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

Route::get('/', function () {
    return json_encode([
        "APP" => "BET_SERVICE",
        "VERSION" => env('VERSION')
    ]);
});


Route::post('sellbet', ['uses' => 'ZirconController@sellBet']);
Route::post('resultbet', ['uses' => 'ZirconController@resultBet']);

Route::get('api/eyecon', ['uses' => 'EyeconController@entry']);

Route::group(['middleware' => FunkyAuthenticationToken::class, 'prefix' => 'Funky'], function (){
    Route::post('Bet/PlaceBet', ['uses' => 'FunkyController@placeBet']);
    Route::post('Bet/SettleBet', ['uses' => 'FunkyController@settleBet']);
});
