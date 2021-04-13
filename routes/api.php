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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('jobs', 'JobOpenController@getAllAPIJobs');

Route::get('job-details/{id}', [
    'uses' => 'JobOpenController@getJobDetailsById'
]);

Route::get('alljob-details/{key_skill}/{desired_location}/{experience}/{min_ctc}/{max_ctc}', [
    'uses' => 'JobOpenController@getJobDetailsBySearch'
]);
