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


Route::group(['middleware' => ['auth:api']], function () {
    Route::get('user', 'API\UserController@loggedInUser');
    Route::get('products', 'API\ProductsController@fetchProducts');
    Route::get('brands', 'API\ProductsController@fetchBrands');
    Route::get('sizes', 'API\ProductsController@fetchSizes');
    Route::get('groups', 'API\ProductsController@fetchProdgroups');
    Route::post('save-payment', 'API\ProductsController@savePayment');
    Route::get('payments', 'PaymentController@fetchPayments');
    Route::get('workers', 'WorkerController@fetchWorkers');
    Route::get('suppliers', 'SupplierController@fetchSuppliers');
});
