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
    Route::patch('/', 'ProfileController@update')->middleware('auth:api');

    Route::get('/tracks', 'ProfileTracksController@index')->middleware('auth:api')->name('me.tracks');
    Route::get('/albums', 'ProfileAlbumsController@index')->middleware('auth:api')->name('me.albums');
    Route::get('/playlists', 'ProfilePlaylistsController@index')->middleware('auth:api')->name('me.playlists');

    Route::get('/followers', 'ProfileFollowersController@index')->middleware('auth:api')->name('me.followers');
    Route::get('/followings', 'ProfileFollowingsController@index')->middleware('auth:api')->name('me.followings');

    Route::get('/notifications', 'NotificationsController@index')->middleware('auth:api')->name('notifications.unread');
    Route::get('/notifications/{id}', 'NotificationsController@show')->middleware('auth:api')->name('notifications.show');
});

Route::prefix('/users')->group(function () {
    Route::get('/{user}', 'UserController@show')->name('users.show');
    Route::delete('/{user}', 'UserController@destroy')->middleware('auth:api');

    Route::get('/{user}/tracks', 'UserTracksController@index')->name('users.tracks');
    Route::get('/{user}/playlists', 'UserPlaylistsController@index')->name('users.playlists');
    Route::get('/{user}/albums', 'UserAlbumsController@index')->name('users.albums');
    Route::get('/{user}/following', 'UserFollowingsController@index')->name('users.following');
    Route::get('/{user}/followers', 'UserFollowersController@index')->name('users.followers');

    Route::post('/{user}/follow', 'FollowersController@store')->middleware('auth:api');
    Route::delete('/{user}/unfollow', 'FollowersController@destroy')->middleware('auth:api');
});

Route::prefix('/tracks')->group(function () {
    Route::post('/', 'TrackController@store')->middleware('auth:api');
    Route::get('/{track}', 'TrackController@show')->name('tracks.show');
    Route::patch('/{track}', 'TrackController@update')->middleware('auth:api')->name('tracks.show');
    Route::delete('/{track}', 'TrackController@destroy')->middleware('auth:api');
    
    Route::patch('/{track}/publish', 'PublishTrackController@update')->middleware('auth:api');

    Route::get('/{track}/replies', 'ReplyTracksController@index')->name('replies.index');
    Route::post('/{track}/replies', 'ReplyTracksController@store')->middleware('auth:api');
    
    Route::post('/{track}/favorite', 'FavoriteTrackController@store')->middleware('auth:api');
    Route::delete('/{track}/unfavorite', 'FavoriteTrackController@destroy')->middleware('auth:api');

    Route::post('/{track}/share', 'ShareTracksController@store')->middleware('auth:api');

    Route::post('/{track}/increment', 'ReproducedTracksController@store');
});

Route::prefix('/replies')->group(function () {
    Route::get('/{reply}', 'ReplyRepliesController@show')->name('replies.show');
    Route::post('/{reply}/replies', 'ReplyRepliesController@store')->middleware('auth:api');
});

Route::prefix('/playlists')->group(function () {
    Route::post('/', 'PlaylistController@store')->middleware('auth:api');
    Route::get('/{playlist}', 'PlaylistController@show')->name('playlists.show');
    Route::patch('/{playlist}', 'PlaylistController@update')->middleware('auth:api');
    Route::delete('/{playlist}', 'PlaylistController@destroy')->middleware('auth:api');

    Route::post('/{playlist}/favorite', 'FavoritePlaylistController@store')->middleware('auth:api');
    Route::delete('/{playlist}/unfavorite', 'FavoritePlaylistController@destroy')->middleware('auth:api');
    
    Route::post('/{playlist}/tracks/{track}/add', 'PlaylistTrackController@store')->middleware('auth:api');
    Route::delete('/{playlist}/tracks/{track}/remove', 'PlaylistTrackController@remove')->middleware('auth:api');

    Route::post('/{playlist}/share', 'SharePlaylistsController@store')->middleware('auth:api');
});

Route::prefix('/albums')->group(function () {
    Route::post('/', 'AlbumController@store')->middleware('auth:api');
    Route::get('/{album}', 'AlbumController@show')->name('albums.show');
    Route::patch('/{album}', 'AlbumController@update')->middleware('auth:api');
    Route::delete('/{album}', 'AlbumController@destroy')->middleware('auth:api');

    Route::patch('/{album}/publish', 'PublishAlbumController@update')->middleware('auth:api');

    Route::post('/{album}/favorite', 'FavoriteAlbumController@store')->middleware('auth:api');
    Route::delete('/{album}/unfavorite', 'FavoriteAlbumController@destroy')->middleware('auth:api');

    Route::post('/{album}/share', 'ShareAlbumsController@store')->middleware('auth:api');
});

Route::prefix('/tags')->group(function () {
    Route::get('/', 'TagController@index')->name('tags.index');
    Route::get('/{tag}', 'TagController@show')->name('tags.show');
    Route::get('/{tag}/tracks', 'TagTracksController@index')->name('tags.tracks');
    Route::get('/{tag}/albums', 'TagAlbumsController@index')->name('tags.albums');
    Route::get('/{tag}/playlists', 'TagPlaylistsController@index')->name('tags.playlists');
});
