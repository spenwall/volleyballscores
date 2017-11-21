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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@scores')->name('home');
Route::get('/', 'HomeController@index')->name('home');

Route::get('/schedule/{leagueName}', 'ScheduleController@index')->name('schedule');
Route::get('/results/{leagueName}/{round}', 'ResultsController@index')->name('results');

Route::get('/teams/{leagueName}', 'TeamsController@index')->name('teams');
Route::get('/team/{id}', 'TeamsController@team')->name('team');