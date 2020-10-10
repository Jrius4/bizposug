<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function fetchPayments()
    {
        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'serial_no';

        if ($rowsPerPage == -1) {
            $rowsPerPage = Payment::count();
        }
        $sortDesc = false;
        if (request()->query('sortDesc') !== null) {
            $sortDesc = request()->query('sortDesc') == 'true' ? true : false;
        } else {
            $sortDesc = false;
        }
        if (request()->query('sortRowsBy') !== null) {
            $sortRowsBy = request()->query('sortRowsBy');
        } else {
            $sortRowsBy = 'serial_no';
        }
        if ($sortRowsBy == 'serial_no') {
            $sortRowsBy = 'serial_no';
        }
        $payments = Payment::with('worker', 'supplier')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('serial_no', 'like', '%' . $query . '%')->orWhere('reciever->name', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('payments', 'sortRowsBy'));
    }
}
