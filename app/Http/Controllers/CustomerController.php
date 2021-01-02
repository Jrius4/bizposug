<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function fetchcustomers()
    {
        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'name';

        if ($rowsPerPage == -1) {
            $rowsPerPage = Customer::count();
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
            $sortRowsBy = 'name';
        }
        if ($sortRowsBy == 'name') {
            $sortRowsBy = 'name';
        }
        $customers = Customer::with('transactions')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->orWhere('address', 'like', '%' . $query . '%')->orWhere('contact', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('customers', 'sortRowsBy'));
    }

    public function fetchCustomer($id)
    {
        $customer = Customer::with('transactions')->find($id);
        $message = "";
        if ($customer == null) {
            $message = 'customer not found!';
        }

        return response()->json(compact('customer', 'message'));
    }

    public function saveCustomer(Request $request)
    {
        $rules = [
            'name' => 'required',
            'contact' => 'required|unique:customers',
        ];

        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()
            ];

            return response()->json($response, 200);
        }

        $customer = new Customer();

        $customer->name = $request->name;
        $customer->contact = $request->contact;
        if ($request->salary != null) $customer->salary = $request->salary;
        if ($request->gender != null) $customer->gender = $request->gender;
        if ($request->d_o_b != null) $customer->d_o_b = $request->d_o_b;
        if ($request->address != null) $customer->address = $request->address;
        if ($request->balance != null) $customer->balance = $request->balance;
        if ($request->biograpy != null) $customer->biograpy = $request->biograpy;
        $message = '';
        if ($customer->save()) {
            $message = 'saved successfully';
        } else {
            $message = 'not saved!';
        }

        return response()->json(compact('message'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'contact' => 'required',
        ];

        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()
            ];

            return response()->json($response, 200);
        }

        $customer = Customer::find($id);
        $message = '';
        if ($customer != null) {
            $customer->name = $request->name;

            if ($request->contact !== $customer->contact) {
                $rules = [
                    'contact' => 'unique:customers',
                ];

                $input = $request->all();

                $validator = Validator::make($input, $rules);

                if ($validator->fails()) {
                    $response = [
                        'errors' => $validator->errors()
                    ];

                    return response()->json($response, 200);
                }
            }
            $customer->contact = $request->contact;
            $customer->salary = $request->salary != null ? $request->salary : $customer->company;
            $customer->gender = $request->gender != null ? $request->gender : $customer->gender;
            $customer->d_o_b = $request->d_o_b != null ? $request->d_o_b : $customer->d_o_b;
            $customer->address = $request->address != null ? $request->address : $customer->address;
            $customer->balance = $request->balance != null ? $request->balance : $customer->balance;
            $customer->biograpy = $request->biograpy != null ? $request->biograpy : $customer->biograpy;



            if ($customer->save()) {
                $message = 'updated successfully';
            } else {
                $message = 'not updated!';
            }
        } else {
            $message = 'customer not found!';
        }

        return response()->json(compact('message'));
    }
}
