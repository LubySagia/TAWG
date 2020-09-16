<?php

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

Route::middleware('auth')->get('/', function () {
 Route::post('upload', 'Admin\ImageController@upload')->name('upload');
});

Route::get('logout', 'Auth\LoginController@logout')->name('logout');


Auth::routes([
    'register' => false,
    'reset' => false,
    "confirm" =>false
]);
Route::get('reset-password', 'Admin\UserController@reset_view')->name('reset_view');
Route::post('reset', 'Admin\UserController@reset')->name('reset');
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('upload', 'Admin\ImageController@upload')->name('upload');
    Route::get('/', 'Admin\DashboardController@index')->name('dasboard');
    Route::get('change-password', 'Admin\UserController@change_password_index')->name('change-password');
    Route::post('change-password', 'Admin\UserController@change_password');
    Route::name('settings.')->prefix('setting')->group(function(){
      Route::get('/', 'Admin\SettingController@index')->name('index');
      Route::post('view', 'Admin\SettingController@view')->name('view');
      Route::post('update', 'Admin\SettingController@update')->name('update');
    });
    Route::name('users.')->prefix('user')->group(function(){
   		Route::get('/', 'Admin\UserController@index')->name('index');
   		Route::post('data', 'Admin\UserController@data')->name('data');
   		Route::post('view', 'Admin\UserController@view')->name('view');
   		Route::post('block', 'Admin\UserController@block')->name('block');
   		Route::post('delete', 'Admin\UserController@delete')->name('delete');
      Route::post('point', 'Admin\UserController@point')->name('point');
    });
    Route::name('categories.')->prefix('category')->group(function(){
   		Route::get('/', 'Admin\CategoryController@index')->name('index');
   		Route::post('all', 'Admin\CategoryController@all')->name('all');
   		Route::post('data', 'Admin\CategoryController@data')->name('data');
   		Route::post('view', 'Admin\CategoryController@view')->name('view');
   		Route::post('create', 'Admin\CategoryController@create')->name('create');
   		Route::post('update', 'Admin\CategoryController@update')->name('update');
   		Route::post('delete', 'Admin\CategoryController@delete')->name('delete');
    });
    Route::name('articles.')->prefix('article')->group(function(){
   		Route::get('/', 'Admin\ArticleController@index')->name('index');
   		Route::post('data', 'Admin\ArticleController@data')->name('data');
   		Route::post('view', 'Admin\ArticleController@view')->name('view');
   		Route::post('block', 'Admin\ArticleController@block')->name('block');
   		Route::post('create', 'Admin\ArticleController@create')->name('create');
   		Route::post('update', 'Admin\ArticleController@update')->name('update');
   		Route::post('delete', 'Admin\ArticleController@delete')->name('delete');
      Route::post('hot', 'Admin\ArticleController@hot')->name('hot');
    });
    Route::name('clubs.')->prefix('club')->group(function(){
   		Route::get('/', 'Admin\ClubController@index')->name('index');
   		Route::post('data', 'Admin\ClubController@data')->name('data');
   		Route::post('view', 'Admin\ClubController@view')->name('view');
   		Route::post('block', 'Admin\ClubController@block')->name('block');
   		Route::post('create', 'Admin\ClubController@create')->name('create');
   		Route::post('update', 'Admin\ClubController@update')->name('update');
   		Route::post('delete', 'Admin\ClubController@delete')->name('delete');
      Route::post('all', 'Admin\ClubController@all')->name('all');
   		Route::post('add_leader', 'Admin\ClubController@add_leader')->name('add_leader');
   		Route::post('delete_leader', 'Admin\ClubController@delete_leader')->name('delete_leader');
   		Route::get('club_member/{club_id}', 'Admin\ClubController@club_member')->name('club_member');
   		Route::post('member_list/{club_id}', 'Admin\ClubController@member_list')->name('member_list');
   		Route::post('delete_member', 'Admin\ClubController@delete_member')->name('delete_member');
      Route::post('allow_member', 'Admin\ClubController@allow_member')->name('allow_member');
      Route::post('allow_member_array', 'Admin\ClubController@allow_member_array')->name('allow_member_array');
      Route::post('delete_member_array', 'Admin\ClubController@delete_member_array')->name('delete_member_array');
      Route::get('club_suggestion/{club_id}', 'Admin\ClubController@club_suggestion')->name('club_suggestion');
      Route::post('suggestion_list/{club_id}', 'Admin\ClubController@suggestion_list')->name('suggestion_list');
      Route::post('delete_suggestion', 'Admin\ClubController@delete_suggestion')->name('delete_suggestion');
      Route::post('view_suggestion', 'Admin\ClubController@view_suggestion')->name('view_suggestion');
    });
    Route::name('events.')->prefix('event')->group(function(){
      Route::get('/', 'Admin\EventController@index')->name('index');
      Route::post('data', 'Admin\EventController@data')->name('data');
      Route::post('view', 'Admin\EventController@view')->name('view');
      Route::post('status', 'Admin\EventController@status')->name('status');
      Route::post('create', 'Admin\EventController@create')->name('create');
      Route::post('update', 'Admin\EventController@update')->name('update');
      Route::post('delete', 'Admin\EventController@delete')->name('delete');
      Route::post('private_status', 'Admin\EventController@private_status')->name('private_status');
      Route::get('member/{event_id}', 'Admin\EventController@view_member')->name('view_member');
      Route::post('member_list/{event_id}', 'Admin\EventController@member_list')->name('member_list');
      Route::post('delete_member', 'Admin\EventController@delete_member')->name('delete_member');
      Route::post('delete_member_array', 'Admin\EventController@delete_member_array')->name('delete_member_array');
      Route::post('checkin_member_array', 'Admin\EventController@checkin_member_array')->name('checkin_member_array');
      Route::get('comment/{event_id}', 'Admin\EventController@list_comment')->name('list_comment');
      Route::post('comment_list/{event_id}', 'Admin\EventController@comment_list')->name('comment_list');
      Route::post('view_comment', 'Admin\EventController@view_comment')->name('view_comment');
      Route::post('delete_comment_report', 'Admin\EventController@delete_comment_report')->name('delete_comment_report');      
      Route::post('delete_comment_array', 'Admin\EventController@delete_comment_array')->name('delete_comment_array');
    });

    Route::name('gifts.')->prefix('gift')->group(function(){
      Route::get('/', 'Admin\GiftController@index')->name('index');
      Route::post('data', 'Admin\GiftController@data')->name('data');
      Route::post('view', 'Admin\GiftController@view')->name('view');
      Route::post('create', 'Admin\GiftController@create')->name('create');
      Route::post('update', 'Admin\GiftController@update')->name('update');
      Route::post('delete', 'Admin\GiftController@delete')->name('delete');
      Route::post('all', 'Admin\GiftController@all')->name('all');
    });
    Route::name('gifts_log.')->prefix('gift_log')->group(function(){
      Route::get('/', 'Admin\GiftLogController@index')->name('index');
      Route::post('data', 'Admin\GiftLogController@data')->name('data');
      Route::post('view', 'Admin\GiftLogController@view')->name('view');
      Route::post('create', 'Admin\GiftLogController@create')->name('create');
      Route::post('update', 'Admin\GiftLogController@update')->name('update');
      Route::post('delete', 'Admin\GiftLogController@delete')->name('delete');
      Route::post('status', 'Admin\GiftLogController@status')->name('status');
    });
});

