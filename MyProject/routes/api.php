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

Route::middleware('auth:api')->get('/users', 'UserController@index');
Route::middleware('auth:api')->get('/rooms', 'ChatRoomController@index');
Route::middleware('auth:api')->get('/rooms/{room_id}/messages', 'MessageController@index');
Route::middleware('auth:api')->get('/new-messages', 'MessageController@newIndex');
Route::middleware('auth:api')->post('/rooms/{room_id}/file', 'MessageController@fileStore');
Route::middleware('auth:api')->post('/rooms', 'ChatRoomController@store');
Route::middleware('auth:api')->put('/rooms/{room_id}', 'ChatRoomController@update');
Route::middleware('auth:api')->post('/check-at', 'ChatRoomController@checkAt');

