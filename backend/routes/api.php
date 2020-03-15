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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//   return $request->user();
// });


// Route::post('/user', 'DashboardController@user');

// Route::post('/authorize', 'DashboardController@authorize');

// Route::post('/dashboard1', 'DashboardController@dashboard1');

// Route::post('/dashboard', 'DashboardController@dashboard');

Route::get('/investor-report', 'ReportController@getInvestorReport');

Route::get('/detail-report', 'ReportController@getDetailReport');





