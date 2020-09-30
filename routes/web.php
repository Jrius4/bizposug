<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', 'WebController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth'], 'prefix' => 'feautures'], function () {
    Route::get('/products', 'FeautureViewController@productsView');
    Route::get('/cashup', 'FeautureViewController@cashUpView');
    Route::get('/history', 'FeautureViewController@historyView');
    Route::get('/sales', 'FeautureViewController@salesView');
    Route::get('/suppliers', 'FeautureViewController@suppliersView');
    Route::get('/customers', 'FeautureViewController@customersView');
    Route::get('/settings', 'FeautureViewController@settingsView');
});
