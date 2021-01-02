<?php

namespace App\Http\Controllers;

use App\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkerController extends Controller
{
    public function fetchWorkers()
    {
        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'name';

        if ($rowsPerPage == -1) {
            $rowsPerPage = Worker::count();
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
        $workers = Worker::with('payments')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->orWhere('role', 'like', '%' . $query . '%')->orWhere('address', 'like', '%' . $query . '%')->orWhere('contact', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('workers', 'sortRowsBy'));
    }

    public function fetchWorker($id)
    {
        $worker = Worker::with('payments')->find($id);
        $message = "";
        if ($worker == null) {
            $message = 'worker not found!';
        }

        return response()->json(compact('worker', 'message'));
    }

    public function saveworker(Request $request)
    {
        $rules = [
            'name' => 'required',
            'contact' => 'required|unique:workers',
        ];

        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()
            ];

            return response()->json($response, 200);
        }

        $worker = new Worker();

        $worker->name = $request->name;
        $worker->contact = $request->contact;
        if ($request->salary != null) $worker->salary = $request->salary;
        if ($request->gender != null) $worker->gender = $request->gender;
        if ($request->d_o_b != null) $worker->d_o_b = $request->d_o_b;
        if ($request->role != null) $worker->role = $request->role;
        if ($request->address != null) $worker->address = $request->address;
        if ($request->balance != null) $worker->balance = $request->balance;
        if ($request->biograpy != null) $worker->biograpy = $request->biograpy;
        $message = '';
        if ($worker->save()) {
            $message = 'saved successfully';
        } else {
            $message = 'not saved!';
        }

        return response()->json(compact('message'));
    }

    public function updateWorker(Request $request, $id)
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

        $worker = Worker::find($id);
        $message = '';
        if ($worker != null) {
            $worker->name = $request->name;

            if ($request->contact !== $worker->contact) {
                $rules = [
                    'contact' => 'unique:workers',
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
            $worker->contact = $request->contact;
            $worker->salary = $request->salary != null ? $request->salary : $worker->company;
            $worker->gender = $request->gender != null ? $request->gender : $worker->gender;
            $worker->d_o_b = $request->d_o_b != null ? $request->d_o_b : $worker->d_o_b;
            $worker->role = $request->role != null ? $request->role : $worker->role;
            $worker->address = $request->address != null ? $request->address : $worker->address;
            $worker->balance = $request->balance != null ? $request->balance : $worker->balance;
            $worker->biograpy = $request->biograpy != null ? $request->biograpy : $worker->biograpy;



            if ($worker->save()) {
                $message = 'updated successfully';
            } else {
                $message = 'not updated!';
            }
        } else {
            $message = 'worker not found!';
        }

        return response()->json(compact('message'));
    }
}
