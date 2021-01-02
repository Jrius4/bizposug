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
    Route::get('products/{id}', 'API\ProductsController@fetchProduct');
    Route::get('brands', 'API\ProductsController@fetchBrands');
    Route::get('brands/{id}', 'BrandController@fetchBrand');
    Route::get('sizes', 'API\ProductsController@fetchSizes');
    Route::get('sizes/{id}', 'SizeController@fetchsize');
    Route::get('groups', 'API\ProductsController@fetchProdgroups');
    Route::get('groups/{id}', 'GroupController@fetchgroup');
    Route::post('save-sale', 'API\ProductsController@savePayment');
    Route::get('payments', 'PaymentController@fetchPayments');
    Route::get('payments/{id}', 'PaymentController@fetchPayment');
    Route::post('store-service', 'BusinserviceController@saveService');
    Route::post('store-service/{id}', 'BusinserviceController@updateService');
    Route::get('services', 'BusinserviceController@fetchServices');
    Route::get('services/{id}', 'BusinserviceController@fetchService');
    Route::get('summary-payments', 'PaymentController@summaryPayments');
    Route::get('workers', 'WorkerController@fetchWorkers');
    Route::get('workers/{id}', 'WorkerController@fetchWorker');
    Route::get('suppliers', 'SupplierController@fetchSuppliers');
    Route::get('suppliers/{id}', 'SupplierController@fetchSupplier');
    Route::get('transactions', "API\TransactionController@fetchTransactions");
    Route::get('summary-transactions', 'API\TransactionController@summaryTransactions');
    Route::post('save-product', 'API\ProductsController@saveProduct');
    Route::post('save-product/{id}', 'API\ProductsController@updateProduct');
    Route::post('save-supplier', 'SupplierController@saveSupplier');
    Route::post('save-supplier/{id}', 'SupplierController@updateSupplier');
    Route::post('save-worker', 'WorkerController@saveWorker');
    Route::post('save-worker/{id}', 'WorkerController@updateWorker');
    Route::post('store-payment', 'PaymentController@storePayment');
    Route::post('save-brand', 'BrandController@saveBrand');
    Route::post('save-brand/{id}', 'BrandController@updateBrand');
    Route::post('save-group', 'GroupController@savegroup');
    Route::post('save-group/{id}', 'GroupController@updategroup');
    Route::post('save-size', 'SizeController@savesize');
    Route::post('save-size/{id}', 'SizeController@updatesize');
    Route::get('customers', 'CustomerController@fetchcustomers');
    Route::get('customers/{id}', 'CustomerController@fetchCustomer');
    Route::post('save-customer', 'CustomerController@saveCustomer');
    Route::post('save-customer/{id}', 'CustomerController@updateCustomer');

    Route::get('accounts-details', 'FinanceController@fetchAccountDetails');
    Route::post('deposit', 'FinanceController@deposit');
    Route::get('financial-list', 'FinanceController@FinancialList');
    // Route::get('income-statement', 'FinstatementController@index');
});
Route::get('income-statements', 'FinstatementController@index');
Route::get('product-sales', 'SaleController@fetchSales');
Route::get('summary-product-sales', 'SaleController@summarySales');
Route::get('summary-product-sales-categories', 'SaleController@summarySalesCategories');
