<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

// Route::get('/testpdf','Internship\MainController@testPDF');
Route::group(['middleware' => ['jwt.verify']], function() {

    Route::group(['namespace' => 'Master'], function () {
        Route::group(['middleware' => ['role.verify:superadministrator']], function(){
            Route::get('/role','RoleController@getData');
            Route::get('/role/all','RoleController@listQuery');
            Route::post('/role','RoleController@store');
            Route::post('/role/{id}','RoleController@update');
            Route::delete('/role/{id}','RoleController@destroy');
        });
    });
    Route::group(['namespace' => 'User'], function () {
        Route::group(['middleware' => ['role.verify:superadministrator']], function(){
            Route::get('/manage-admin','AdministratorController@getData');
            Route::post('/manage-admin','AdministratorController@store');
            Route::post('/manage-admin/{id}','AdministratorController@update');
            Route::delete('/manage-admin/{id}','AdministratorController@destroy');
        });
        Route::group(['middleware' => ['role.verify:superadministrator|administrator']], function(){
            Route::get('/manage-customer','CustomerController@getData');
            Route::post('/manage-customer','CustomerController@store');
            Route::post('/manage-customer/{id}','CustomerController@update');
            Route::delete('/manage-customer/{id}','CustomerController@destroy');
        });
        Route::get('/user', 'MainController@getAuthenticatedUser');
        Route::post('/user/changepassword', 'MainController@changepassword');
    });
});

Route::post('/logout', 'AuthController@logout');
Route::post('/login', 'AuthController@login');
Route::post('/registration', 'AuthController@registration');