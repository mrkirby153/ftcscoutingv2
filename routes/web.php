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


Route::impersonate();
Auth::routes();

Route::get('/invite/{member}/accept', 'TeamMemberController@acceptInvite')->name('invite.accept');

Route::get('/survey/{survey}/table', 'SurveyController@showTable');
Route::get('/survey/{survey}/excel', 'SurveyController@excel')->name('survey.export');
Route::get('{any}', function () {
    return view('application');
})->where('any', '.*');