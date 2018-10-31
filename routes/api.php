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

Route::prefix('/auth')->group(function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
});

Route::prefix('/users')->group(function () {
    Route::get('/{user}', 'UserController@show')->name('users.show');
});

Route::prefix('/tracks')->group(function () {
    Route::post('/', 'TrackController@store')->middleware('auth:api');
    Route::get('/{track}', 'TrackController@show')->name('tracks.show');
});

Route::prefix('/playlists')->group(function () {
    Route::post('/', 'PlaylistController@store')->middleware('auth:api');
    Route::get('/{playlist}', 'PlaylistController@show')->name('playlists.show');
});

Route::prefix('/albums')->group(function () {
    Route::post('/', 'AlbumController@store')->middleware('auth:api');
    Route::get('/{album}', 'AlbumController@show');
});
