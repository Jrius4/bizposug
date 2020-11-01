<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancialReportsController extends Controller
{
    public function cashInflows()
    {
        return view('reports.financials.cash-flows');
    }


    public function balanceSheet()
    {
        return view('reports.financials.balance-sheet');
    }
}
