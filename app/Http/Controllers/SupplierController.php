<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function fetchSuppliers()
    {
        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'name';

        if ($rowsPerPage == -1) {
            $rowsPerPage = Supplier::count();
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
        $suppliers = Supplier::with('payments')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->orWhere('company', 'like', '%' . $query . '%')->orWhere('contact', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('suppliers', 'sortRowsBy'));
    }

    public function fetchSupplier($id)
    {
        $supplier = Supplier::with('products', 'payments')->find($id);
        $message = "";
        if ($supplier == null) {
            $message = 'supplier not found!';
        }

        return response()->json(compact('supplier', 'message'));
    }

    public function saveSupplier(Request $request)
    {
        $rules = [
            'name' => 'required',
            'contact' => 'required|unique:suppliers',
        ];

        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()
            ];

            return response()->json($response, 200);
        }

        $supplier = new Supplier();

        $supplier->name = $request->name;
        $supplier->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
        $supplier->contact = $request->contact;
        if ($request->company != null) $supplier->company = $request->company;
        if ($request->address != null) $supplier->address = $request->address;
        if ($request->balance != null) $supplier->balance = $request->balance;
        if ($request->biograpy != null) $supplier->biograpy = $request->biograpy;
        $message = '';
        if ($supplier->save()) {
            $message = 'saved successfully';
        } else {
            $message = 'not saved!';
        }

        return response()->json(compact('message'));
    }

    public function updateSupplier(Request $request, $id)
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

        $supplier = Supplier::find($id);
        $message = '';
        if ($supplier != null) {
            $supplier->name = $request->name;
            $supplier->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
            if ($request->contact !== $supplier->contact) {
                $rules = [
                    'contact' => 'unique:suppliers',
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
            $supplier->contact = $request->contact;
            $supplier->company = $request->company != null ? $request->company : $supplier->company;
            $supplier->address = $request->address != null ? $request->address : $supplier->address;
            $supplier->balance = $request->balance != null ? $request->balance : $supplier->balance;
            $supplier->biograpy = $request->biograpy != null ? $request->biograpy : $supplier->biograpy;



            if ($supplier->save()) {
                $message = 'updated successfully';
            } else {
                $message = 'not updated!';
            }
        } else {
            $message = 'supplier not found!';
        }

        return response()->json(compact('message'));
    }
}
