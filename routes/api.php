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
Route::group(['prefix' => 'members', 'middleware' => 'auth:api'], function () {
    Route::put('/{team}/invite', 'TeamMemberController@inviteMember')->name('team.member.create');
    Route::delete('/{member}', 'TeamMemberController@removeMember')->name('team.member.remove');
    Route::patch('/{member}/accept', 'TeamMemberController@acceptInvite')->name('team.member.accept');
});

Route::group(['prefix' => 'teams'], function () {
    Route::put('/create', 'TeamController@createTeam')->name('team.create')->middleware('auth:api');
    Route::get('/{team}', 'TeamController@getTeam')->name('team.get');
    Route::get('/', 'TeamController@getTeams')->name('team.list')->middleware('auth:api');
    Route::put('/{team}/createSurvey', 'SurveyController@createSurvey')->name('survey.create')->middleware('auth:api');
});

Route::group(['prefix' => 'survey', 'middleware' => 'auth:api'], function () {
    Route::get('/{survey}', 'SurveyController@get')->name('survey.get');
    Route::put('/{survey}', 'SurveyController@createQuestion')->name('survey.question.create');
    Route::delete('/{survey}', 'SurveyController@deleteSurvey')->name('survey.delete');
    Route::patch('/{survey}/questions/{question}', 'SurveyController@editQuestion')->name('survey.question.update');
    Route::delete('/{survey}/questions/{question}', 'SurveyController@deleteQuestion')->name('survey.question.delete');
});