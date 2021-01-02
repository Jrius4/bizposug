<?php

namespace App\Http\Controllers;

use App\Account;
use App\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinanceController extends Controller
{
    public function financesView()
    {
        return view('feautures.finances');
    }

    public function fetchAccountDetails()
    {
        $account = Account::find(1);
        $message = "";
        if ($account != null) {
            $message = "Account Found";
            return response()->json(compact('account', 'message'));
        } else {
            $message = 'Account Not Found';
            return response()->json(compact('account', 'message'));
        }
    }

    public function deposit(Request $request)
    {
        $account3 = Account::find(3);
        $account1 = Account::find(1);

        $rules = [
            'balance' => 'required',
            'type' => 'required'
        ];

        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()
            ];

            return response()->json($response, 200);
        }

        $type = ($request->type != null ? $request->type : 'deposit');

        $balance = $request->balance;
        $details = $request->details != null ? $request->details : null;
        $previous_balance = $account3->balance;
        $accumulate_balance = $account3->balance;


        if ($type == 'withdraw') $accumulate_balance -= $balance;
        else if ($type == 'deposit') $accumulate_balance += $balance;

        $finance_trans = new Finance();

        $account3->balance = $accumulate_balance;

        if ($account3->save()) {
            $old_bal = $account1->balance;
            $new_bal = $account1->balance;
            if ($type == 'withdraw') $new_bal -= $balance;
            else if ($type == 'deposit') $new_bal += $balance;

            $account1->balance = $new_bal;
            if ($account1->save()) {
                $finance_trans->create([
                    'amount' => $balance,
                    'type' => $type,
                    'current_balance' => $accumulate_balance,
                    'previous_balance' =>  $previous_balance,
                    'previous_main_balance' =>  $old_bal,
                    'current_main_balance' =>  $new_bal,
                    'details' =>  $details,
                ]);
                $account = $account1;
                $message = 'Deposited successfully';
                return response()->json(compact('message', 'account'));
            }
        }
    }

    public function FinancialList()
    {
        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'created_at';

        if ($rowsPerPage == -1) {
            $rowsPerPage = Finance::count();
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
            $sortRowsBy = 'created_at';
        }
        if ($sortRowsBy == 'created_at') {
            $sortRowsBy = 'created_at';
        }
        $items = Finance::orderBy($sortRowsBy, ($sortDesc ? 'asc' : 'desc'))->where('type', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('items', 'sortRowsBy'));
    }
}
