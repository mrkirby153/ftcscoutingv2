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
Route::group(['prefix' => 'members'], function(){
    Route::put('/{team}/invite', 'TeamMemberController@inviteMember')->name('team.member.create');
    Route::delete('/{member}', 'TeamMemberController@removeMember')->name('team.member.remove');
});

Route::group(['prefix'=>'teams'], function(){
    Route::put('/create', 'TeamController@createTeam')->name('team.create')->middleware('auth:api');
    Route::get('/', 'TeamController@getTeams')->name('team.list')->middleware('auth:api');
});