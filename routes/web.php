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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();


Route::group(['middleware' => 'auth'], function () {

    Route::get('', 'CaseController@index');
    
    Route::get('/data', 'CaseController@data');
    
    //Route::get('/create', 'CaseController@create');
    //Route::get('/{id}/edit', 'CaseController@edit')->where(['id' => '[0-9]+']);
    //Route::post('/store', 'CaseController@store');
    // Route::post('/destroy', 'CaseController@destroy');

    Route::get('/{case_id}/send', 'CaseController@Send')->where(['case_id' => '[1-9]+']);


    Route::get('/{case_id}/stations', 'StationController@index')->where(['case_id' => '[1-9]+']);

    Route::get('/{case_id}/stations/data', 'StationController@data')->where(['case_id' => '[1-9]+']);

        Route::get('/{case_id}/stations/{case_region_id}/{station_id}/send', 'StationController@send')
        ->where(['case_id' => '[1-5]+'])->where(['case_region_id' => '[5-9]'])->where(['station_id' => '[1-9]+']);

    Route::get('/{case_id}/stations/{case_region_id}/{station_id}/detail', 'StationController@detail')
        ->where(['case_id' => '[1-5]+'])->where(['case_region_id' => '0|[5-9]'])->where(['station_id' => '[0-9]+']);

    Route::post('/{case_id}/stations/store', 'StationController@store')->where(['case_id' => '[0-9]+']);

    //Route::post('/{case_id}/stations/destroy', 'StationController@destroy')->where(['case_id' => '[0-9]+']);


    Route::get('/{case_id}/stations/{case_region_id}/getStationDetails/{id}', 'StationController@getStationDetails')
    ->where(['case_id' => '[1-5]+'])->where(['case_region_id' => '[5-9]']);

    Route::get('/{case_id}/stations/IsStationValid/{id}', 'StationController@IsStationValid')
    ->where(['case_id' => '[1-5]+']);   

    Route::get('/stations/isStationValid2/{id}', 'StationController@isStationValid2')
    ->where(['id' => '[0-9\^]+']);   
    
    Route::get('/{case_id}/stations/getStations/{$case_region_id}', 'StationController@getStations')
    ->where(['case_id' => '[1-5]+'])->where(['case_region_id' => '[5-9]']);   


    Route::get('/stations/getStations2/{id}', 'StationController@getStations2')
    ->where(['id' => '[0-9\^]+']);   
    
    Route::get('/stations/getStationDetails2/{id}', 'StationController@getStationDetails2')
    ->where(['id' => '[0-9\^]+']);   

    Route::get('/stations/getCaseItems/{id}', 'StationController@getCaseItems')
    ->where(['id' => '[0-9\^]+']);   

    Route::get('/stations/hasStationBeenRegistered/{id}', 'StationController@hasStationBeenRegistered')
    ->where(['id' => '[0-9\^]+']);   

    Route::get('/stations/{$case_region_id}/getStations', 'StationController@getStations')
    ->where(['case_id' => '[0-9]+'])->where(['case_region_id' => '[5-9]']);   

    Route::get('/home', 'HomeController@index')->name('home');
    
});