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

Route::group(['middleware' => ['auth']], function () {
    Route::get('inventory', 'FeautureViewController@inventoryView');
    Route::get('cashout', 'FeautureViewController@cashOutView');

    Route::get('sales', 'FeautureViewController@salesView');
    Route::get('suppliers', 'FeautureViewController@suppliersView');
    Route::get('customers', 'FeautureViewController@customersView');
    Route::get('workers', 'FeautureViewController@workersView');
    Route::get('settings', 'FeautureViewController@settingsView');
    Route::get('payments', 'FeautureViewController@paymentsView');
    Route::get('finances', 'FinanceController@financesView');
    Route::group(['prefix' => 'reports'], function () {
        Route::get('', 'FeautureViewController@reportsView');
        Route::get('/graphical_summary_transactions', 'ReportsController@transactionsSummaryGraphicalView')->name('transactions.summary.graphical');
        Route::get('/graphical_summary_payments', 'PaymentController@graphicalView')->name('payments.summary.graphical');
        Route::get('/graphical_summary_sales', 'SaleController@salesGraphicalView')->name('sales.summary.graphical');
        Route::get('/graphical_categories_summary_sales', 'SaleController@salesCategoriesGraphicalView')->name('sales.categories.summary.graphical');
        Route::get('/reports_financial_cash-inflow', 'FinancialReportsController@cashInflows')->name('reports.financial.cash-inflow');
        Route::get('/reports_financial_balance-sheet', 'FinancialReportsController@balanceSheet')->name('reports.financial.balance-sheet');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('brands', 'BrandController@index');
        Route::get('product-groups', 'GroupController@index');
        Route::get('sizes', 'SizeController@index');
        Route::get('services', 'BusinserviceController@index');
    });
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/print/reciept', 'PDFController@printReceipt');
});
