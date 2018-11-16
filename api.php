<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'API\UserController@details');
    Route::post('match/addmatch', 'MatchController@addMatch');
    Route::post('match/response/{match}', 'MatchController@response');
    Route::post('match/invites', 'MatchController@invites');
    Route::post('match/accepted', 'MatchController@accepted');
    Route::post('match/{match}', 'MatchController@newMove');
});

