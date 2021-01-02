<?php

namespace App\Http\Controllers;

use App\Businservice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class BusinserviceController extends Controller
{
    public function index()
    {
        return view('feautures.services');
    }

    public function fetchServices()
    {
        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'serial_no';

        if ($rowsPerPage == -1) {
            $rowsPerPage = Businservice::count();
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
        $services = Businservice::with('customer')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('serial_no', 'like', '%' . $query . '%')->orWhere('served->name', 'like', '%' . $query . '%')->orWhere('served->serial_no', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('services', 'sortRowsBy'));
    }

    public function fetchService($id)
    {
        $service = Businservice::with('customer')->find($id);
        $message = '';
        if ($service != null) {
            $message = 'service not found';
        }
        return response()->json(compact('message', 'service'));
    }
    public function saveService(Request $request)
    {
        // $rules = [
        //     'type_payment' => 'required',
        //     'paid' => 'required',
        // ];

        // $input = $request->all();

        // $validator = Validator::make($input, $rules);

        // if ($validator->fails()) {
        //     $response = [
        //         'errors' => $validator->errors()
        //     ];

        //     return response()->json($response, 200);
        // }

        $payment = new Businservice();

        $payment->serial_no = Str::uuid();
        $payment->items = $request->items;
        if ($request->customer_id != null) $payment->customer_id = $request->customer_id;
        if ($request->client_details != null) $payment->client_details = $request->client_details;
        if ($request->balance != null) $payment->balance_due = $request->balance;
        if ($request->serviceName != null) $payment->service_name = $request->serviceName;
        if ($request->prevBalance != null) $payment->prev_balance = $request->prevBalance;
        if ($request->item_amount != null) $payment->item_amount = $request->item_amount;
        if ($request->type_service != null) $payment->service_type = $request->type_service;
        if ($request->description != null) $payment->comment = $request->description;
        if ($request->received_by != null) $payment->served_by = $request->received_by;
        if ($request->amountPaid != null) $payment->amount_paid = $request->amountPaid;
        if ($request->amountAgreed != null) $payment->amount_paid = $request->amountAgreed;
        if ($request->cost != null) $payment->cost = $request->cost;
        if ($request->profit != null) $payment->profit = $request->profit;
        $payment->sys_user = auth()->user()->name;
        $message = '';
        if ($payment->save()) {

            $message = 'saved successfully';
        } else {
            $message = 'not saved!';
        }

        return response()->json(compact('message'));
    }
    public function updateService(Request $request, $id)
    {
    }
}
