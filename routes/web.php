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

// Route::get('/', function () {
//     // return view('welcome');
// });

Route::get('/', 'LinksController@index')->name('home');
Route::get('/dashboard/link/create', 'LinksController@create')->name('link.create');
Route::post('/store', 'LinksController@store')->name('link.store');
Route::get('/{code}', 'LinksController@show', function ($code){

})->name('link.show');
Route::get('/dashboard/stats/{filter}', 'LinksController@stats', function ($filter){

})->name('stats');
Route::get('/success', 'LinksController@success')->name('link.success');