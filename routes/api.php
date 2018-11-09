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

Route::prefix('/me')->group(function () {
    Route::get('/', 'ProfileController@show')->middleware('auth:api');

    Route::get('/tracks', 'ProfileTracksController@index')->middleware('auth:api')->name('me.tracks');
    Route::get('/albums', 'ProfileAlbumsController@index')->middleware('auth:api')->name('me.albums');
    Route::get('/playlists', 'ProfilePlaylistsController@index')->middleware('auth:api')->name('me.playlists');

    Route::get('/followers', 'ProfileFollowersController@index')->middleware('auth:api')->name('me.followers');
    Route::get('/followings', 'ProfileFollowingsController@index')->middleware('auth:api')->name('me.followings');
});

Route::prefix('/users')->group(function () {
    Route::get('/{user}', 'UserController@show')->name('users.show');
    Route::delete('/{user}', 'UserController@destroy')->middleware('auth:api');

    Route::get('/{user}/tracks', 'UserTracksController@index')->name('users.tracks');
    Route::get('/{user}/playlists', 'UserPlaylistsController@index')->name('users.playlists');
    Route::get('/{user}/albums', 'UserAlbumsController@index')->name('users.albums');
    Route::get('/{user}/following', 'UserFollowingsController@index')->name('users.following');
    Route::get('/{user}/followers', 'UserFollowersController@index')->name('users.followers');

    Route::post('/{user}/follow', 'FollowersController@store');
    Route::delete('/{user}/unfollow', 'FollowersController@destroy');
});

Route::prefix('/tracks')->group(function () {
    Route::post('/', 'TrackController@store')->middleware('auth:api');
    Route::get('/{track}', 'TrackController@show')->name('tracks.show');
    Route::delete('/{track}', 'TrackController@destroy')->middleware('auth:api');
    
    Route::patch('/{track}/publish', 'PublishTrackController@update');

    Route::get('/{track}/replies', 'ReplyController@index')->name('replies.index');
    Route::post('/{track}/replies', 'ReplyController@store')->middleware('auth:api');
    
    Route::post('/{track}/favorite', 'FavoriteTrackController@store')->middleware('auth:api');
    Route::delete('/{track}/unfavorite', 'FavoriteTrackController@destroy')->middleware('auth:api');
});

Route::prefix('/replies')->group(function () {
    Route::get('/{reply}', 'ReplyController@show')->name('replies.show');
    Route::post('/{reply}/reply', 'ReplyRepliesController@store');
});

Route::prefix('/playlists')->group(function () {
    Route::post('/', 'PlaylistController@store')->middleware('auth:api');
    Route::get('/{playlist}', 'PlaylistController@show')->name('playlists.show');
    Route::delete('/{playlist}', 'PlaylistController@destroy')->middleware('auth:api');

    Route::post('/{playlist}/favorite', 'FavoritePlaylistController@store')->middleware('auth:api');
    Route::delete('/{playlist}/unfavorite', 'FavoritePlaylistController@destroy')->middleware('auth:api');
    
    Route::post('/{playlist}/tracks/{track}/add', 'PlaylistTrackController@store')->middleware('auth:api');
    Route::delete('/{playlist}/tracks/{track}/remove', 'PlaylistTrackController@remove')->middleware('auth:api');
});

Route::prefix('/albums')->group(function () {
    Route::post('/', 'AlbumController@store')->middleware('auth:api');
    Route::get('/{album}', 'AlbumController@show')->name('albums.show');
    Route::delete('/{album}', 'AlbumController@destroy')->middleware('auth:api');

    Route::patch('/{album}/publish', 'AlbumController@update')->middleware('auth:api');

    Route::post('/{album}/favorite', 'FavoriteAlbumController@store')->middleware('auth:api');
    Route::delete('/{album}/unfavorite', 'FavoriteAlbumController@destroy')->middleware('auth:api');
});
