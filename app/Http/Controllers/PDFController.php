<?php

namespace App\Http\Controllers;

use App\Transaction;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function printReceipt()
    {
        $transID = request()->query('transID');

        $trans = Transaction::find($transID);

        // $data = 'data';
        $pdf = \PDF::loadView('pdf.reciept', compact('trans'));
        $customPaper = array(0, 0, 200, 509);

        return $pdf->setPaper($customPaper, 'portrait')->stream('nsma.pdf');
        // return view('pdf.reciept', compact('trans'));
    }
}
