<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function transactionsSummaryGraphicalView()
    {
        return view('reports.transactions.graphical');
    }
}
