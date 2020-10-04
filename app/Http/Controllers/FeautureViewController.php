<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeautureViewController extends Controller
{
    public function productsView(Request $request)
    {
        $productID = $request->query('productID');

        return view('feautures.products');
    }

    public function cashOutView(Request $request)
    {
        $cashoutID = $request->query('cashoutID');
        return view('feautures.cashout');
    }

    public function historyView(Request $request)
    {
        $reportID = $request->query('reportID');
        return view('feautures.history');
    }

    public function salesView(Request $request)
    {
        $saleId = $request->query('saleId');
        return view('feautures.sales');
    }

    public function suppliersView(Request $request)
    {
        $supplierID = $request->query('supplierID');
        return view('feautures.suppliers');
    }

    public function customersView(Request $request)
    {
        $customerId = $request->query('customerId');
        return view('feautures.customers');
    }

    public function settingsView(Request $request)
    {
        $settingID = $request->query('settingID');
        return view('feautures.settings');
    }
}
