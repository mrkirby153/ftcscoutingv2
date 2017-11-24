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

Route::post('/auth/check', 'AuthorizationController@checkAuthorization')->middleware('auth:api')->name('auth.check');

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
    Route::get('/{team}/surveys', 'SurveyController@showSurveys')->name('survey.list')->middleware('auth:api');
});

Route::group(['prefix' => 'survey', 'middleware' => 'auth:api'], function () {
    Route::get('/{survey}', 'SurveyController@get')->name('survey.get');
    Route::put('/{survey}', 'SurveyController@createQuestion')->name('survey.question.create');
    Route::delete('/{survey}', 'SurveyController@deleteSurvey')->name('survey.delete');
    Route::patch('/{survey}/questions/{question}', 'SurveyController@editQuestion')->name('survey.question.update');
    Route::delete('/{survey}/questions/{question}', 'SurveyController@deleteQuestion')->name('survey.question.delete');
    Route::patch('/{survey}/questions/{question}/type', 'SurveyController@setQuestionType')->name('survey.question.type');
    Route::patch('/{survey}/questions/{question}/order', 'SurveyController@setQuestionOrder')->name('survey.question.order');
    Route::put('/{survey}/response', 'SurveyController@processSubmit')->name('survey.commit');
    Route::get('/{survey}/rankings', 'PinController@rankTeams')->name('survey.rank');
});

Route::group(['prefix'=>'responses', 'middleware'=>'auth:api'], function(){
    Route::get('/{survey}/overview', 'ResponseController@getSurveyResponseOverview')->name('response.overview');
    Route::get('/{survey}/team/{team}', 'ResponseController@getResponsesForTeam')->name('response.team.overview');

    Route::get('/{response}', 'ResponseController@getResponse')->name('response.get');
    Route::delete('/{response}', 'ResponseController@delete')->name('response.delete');
});

Route::group(['prefix' => 'pin', 'middleware' => 'auth:api'], function(){
    Route::get('/question/{question}', 'PinController@getForQuestion')->name('pin.get.by-question');
    Route::put('/question/{question}', 'PinController@create')->name('pin.create');
    Route::get('/survey/{survey}', 'PinController@getSurveyPinData')->name('pin.get.survey');

    Route::get('/response/{response}', 'PinController@getResponsePin')->name('pin.response');

    Route::get('/{id}', 'PinController@getById')->name('pin.get.by-id');
    Route::patch('/{id}', 'PinController@update')->name('pin.update');
    Route::delete('/{id}', 'PinController@delete')->name('pin.delete');

});

Route::group(['prefix' => 'questions', 'middleware' => 'auth:api'], function(){
    Route::get('{id}', 'SurveyController@getQuestion')->name('question.get');
});