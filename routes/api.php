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
Route::post('auth/login', 'Api\AuthController@login');
Route::post('auth/login_facebook', 'Api\AuthController@login_facebook');
Route::post('auth/login_google', 'Api\AuthController@login_google');
Route::post('auth/reset_password', 'Api\UserController@sendMail');
Route::prefix('user')->group(function () {
    Route::post('create', 'Api\UserController@create');
});  	
Route::group(['middleware' => 'auth.jwt'], function ($router) {
    Route::post('auth/logout', 'Api\AuthController@logout');
    Route::post('auth/refresh', 'Api\AuthController@refresh');
    Route::post('auth/me', 'Api\AuthController@me');
    Route::prefix('user')->group(function () {
    	Route::post('update', 'Api\UserController@update');
    	Route::post('avatar', 'Api\UserController@avatar');
    	Route::post('change_password', 'Api\UserController@change_password');
        Route::post('add_point', 'Api\UserController@add_point');
        Route::post('subtract_point', 'Api\UserController@subtract_point');
        
    });
    Route::prefix('category')->group(function () {
    	Route::post('data', 'Api\CategoryController@data');
    });
    Route::prefix('article')->group(function () {
    	Route::post('view', 'Api\ArticleController@view');
    	Route::post('data', 'Api\ArticleController@data');
        Route::post('hot', 'Api\ArticleController@hot');
    });
    Route::prefix('club')->group(function () {
    	Route::post('view', 'Api\ClubController@view');
    	Route::post('data', 'Api\ClubController@data');
    	Route::post('add_member', 'Api\ClubController@add_member');
    	Route::post('allow_member', 'Api\ClubController@allow_member');
        Route::post('suggestion_list', 'Api\ClubController@suggestion_list');
        Route::post('send_suggestion', 'Api\ClubController@send_suggestion');
    });
    Route::prefix('event')->group(function () {
        Route::post('view', 'Api\EventController@view');
        Route::post('data', 'Api\EventController@data');
        Route::post('create', 'Api\EventController@create');
        Route::post('update', 'Api\EventController@update');
        Route::post('delete', 'Api\EventController@delete');
        Route::post('join', 'Api\EventController@join');
        Route::post('unjoin', 'Api\EventController@unjoin');
        Route::post('checkin', 'Api\EventController@checkin');
        Route::post('delete_checkin', 'Api\EventController@delete_checkin');
        Route::post('comment', 'Api\EventController@comment');
        Route::post('comment_update', 'Api\EventController@comment_update');
        Route::post('comment_delete', 'Api\EventController@comment_delete');
        Route::post('comment_report', 'Api\EventController@comment_report');
        Route::post('comment_data', 'Api\EventController@comment_data');
    });
    Route::prefix('gift')->group(function () {
        Route::post('data', 'Api\GiftController@data');
        Route::post('exchange', 'Api\GiftController@exchange');
        Route::post('history', 'Api\GiftController@history');
    });
});