<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Transaction;

class TransactionController extends Controller
{
    public function fetchTransactions()
    {
        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'name';

        if ($rowsPerPage == -1) {
            $rowsPerPage = Transaction::count();
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
        $transactions = Transaction::orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('code', 'like', '%' . $query . '%')->orWhere('type_of_transaction', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('transactions', 'sortRowsBy'));
    }

    public function summaryTransactions(Request $request)
    {
        $date = new Carbon();
        $queryType = $request->query('queryType') !== null ? strtolower($request->query('queryType')) : 'daily';
        $transactions = [];
        if ($queryType == 'daily') {
            $transactions = DB::select('
            SELECT COUNT(*) AS freq, SUM(total) AS total ,
            DATE(created_at) AS transact_day
            FROM transactions
            GROUP BY transact_day ORDER BY transact_day desc;
            ');
        } else if ($queryType == 'daily_by_group') {
            $transactions = DB::select('
            SELECT COUNT(*) AS freq,
            DATE(pa.created_at) AS transact_day, SUM(CASE WHEN pa.type_of_transaction = "Retail Sale" then pa.total ELSE 0 END) AS retailsale,
            SUM(CASE WHEN pa.type_of_transaction = "Whole Sale" then pa.total ELSE 0 END) AS wholesale
            FROM transactions AS pa GROUP BY transact_day ORDER BY transact_day DESC;
            ');
        } else if ($queryType == 'weekly') {
            $transactions = DB::select('
                SELECT COUNT(*) AS freq, SUM(total) AS total,
                FLOOR((DAY(created_at)-1)/7 + 1) AS week_of_the_month,
                MONTHNAME(created_at) AS "month_of_the_year", YEAR(created_at) AS year
                FROM transactions
                GROUP BY year,month_of_the_year,week_of_the_month ORDER BY created_at desc;
            ');
        } else if ($queryType == 'weekly_by_group') {
            $transactions = DB::select('
                SELECT COUNT(*) AS freq,
                FLOOR((DAY(created_at)-1)/7 + 1) AS week_of_the_month,
                MONTHNAME(created_at) AS mnth, YEAR(created_at) AS yr,
                SUM(CASE WHEN pa.type_of_transaction = "Retail Sale" then pa.total ELSE 0 END) AS retailsale,
                SUM(CASE WHEN pa.type_of_transaction = "Whole Sale" then pa.total ELSE 0 END) AS wholesale
                FROM transactions AS pa GROUP BY week_of_the_month,mnth,yr ORDER BY pa.created_at DESC;
                ');
        } else if ($queryType == 'monthly') {
            $transactions = DB::select('
            SELECT COUNT(*) AS freq, SUM(total) AS total,
            MONTHNAME(created_at) AS "mnth", YEAR(created_at) AS yr
            FROM transactions as pa
            GROUP BY yr,mnth ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'monthly_by_group') {
            $transactions = DB::select('
                SELECT COUNT(*) AS freq,
                MONTHNAME(created_at) AS mnth, YEAR(created_at) AS yr,
                SUM(CASE WHEN pa.type_of_transaction = "Retail Sale" then pa.total ELSE 0 END) AS retailsale,
                SUM(CASE WHEN pa.type_of_transaction = "Whole Sale" then pa.total ELSE 0 END) AS wholesale
                FROM transactions AS pa GROUP BY mnth,yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'yearly') {
            $transactions = DB::select('
            SELECT COUNT(*) AS freq, SUM(total) AS total,
            YEAR(created_at) AS yr
            FROM transactions as pa
            GROUP BY yr ORDER BY pa.created_at;
            ');
        } else if ($queryType == 'yearly_by_group') {
            $transactions = DB::select('
            SELECT
            YEAR(created_at) AS yr,
            SUM(CASE WHEN pa.type_of_transaction = "Retail Sale" then pa.total ELSE 0 END) AS retailsale,
            SUM(CASE WHEN pa.type_of_transaction = "Whole Sale" then pa.total ELSE 0 END) AS wholesale
            FROM transactions AS pa GROUP BY yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'groups') {
            $transactions = DB::select('
                SELECT COUNT(*) AS freq,
                SUM(CASE WHEN pa.type_of_transaction = "Retail Sale" then pa.total ELSE 0 END) AS retailsale,
                SUM(CASE WHEN pa.type_of_transaction = "Whole Sale" then pa.total ELSE 0 END) AS wholesale
                FROM transactions AS pa GROUP BY pa.type_of_transaction ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'all') {
            $transactions = DB::select('
            SELECT COUNT(*) AS freq, SUM(total) AS total
            FROM transactions;
            ');
        } else if ($queryType == 'interval') {
            $start = $request->query('start');
            $end = $request->query('end');

            $startDate = $date->parse($start)->format('Y-m-d');
            $endDate = $date->parse($end)->format('Y-m-d');
            $transactions = DB::select("
            SELECT COUNT(*) AS freq,
            '" . $startDate . "_" . $endDate . "' AS p_period,SUM(ex.total) AS total
            FROM transactions AS ex WHERE ex.created_at
            BETWEEN DATE('" . $startDate . "') AND DATE('" . $endDate . "') GROUP BY p_period;
            ");
        } else if ($queryType == 'interval_by_group') {
            $start = $request->query('start');
            $end = $request->query('end');

            $startDate = $date->parse($start)->format('Y-m-d');
            $endDate = $date->parse($end)->format('Y-m-d');


            $transactions =  DB::select("SELECT COUNT(*) AS freq,
                '" . $startDate . "_" . $endDate . "' AS p_period,
                SUM(pa.total) AS total,
                SUM(CASE WHEN pa.type_of_transaction = 'Retail Sale' then pa.total ELSE 0 END) AS retailsale,
                SUM(CASE WHEN pa.type_of_transaction = 'Whole Sale' then pa.total ELSE 0 END) AS wholesale
                FROM transactions AS pa WHERE pa.created_at
                BETWEEN DATE('" . $startDate . "') AND DATE('" . $endDate . "')
                GROUP BY p_period ORDER BY pa.created_at;");
        }

        $total = count($transactions);
        $page = $request->query('page') != null ? $request->query('page') : 1;
        $rowsPerPage = $request->query('rowsPerPage') != null ? $request->query('rowsPerPage') : 10;
        if ($rowsPerPage == "-1") $rowsPerPage = $total;
        $offset = ($page * $rowsPerPage) - $rowsPerPage;
        $transactions = new LengthAwarePaginator(
            array_slice($transactions, $offset, $rowsPerPage, false),
            $total,
            $rowsPerPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );




        return response()->json(compact('transactions', 'queryType'));
    }

    public function productSales(Request $request)
    {

        $sales = Transaction::orderBy('created_at', 'asc')->get();
        $items = array();
        foreach ($sales as $sale) {
            foreach ($sale->products as $product) {
                array_push($items, array_merge(
                    $product,
                    ['amount' => ($product['qty'] * ($sale['type_of_transaction'] == 'Whole Sale' ? $product['wholesale_price'] : $product['retailsale_price']))],
                    ($sale['type_of_transaction'] == 'Whole Sale' ? ['wholeprice' => $product['wholesale_price'], 'retailprice' => 0] : ['wholeprice' => 0, 'retailprice' => $product['retailsale_price']]),
                    ['sales_day' => $sale['created_at']]
                ));
            }
        }



        $total = count($items);
        $page = $request->query('page') != null ? $request->query('page') : 1;
        $rowsPerPage = $request->query('rowsPerPage') != null ? $request->query('rowsPerPage') : 10;
        if ($rowsPerPage == "-1") $rowsPerPage = $total;
        $offset = ($page * $rowsPerPage) - $rowsPerPage;
        $sales = new LengthAwarePaginator(
            array_slice($items, $offset, $rowsPerPage, false),
            $total,
            $rowsPerPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );



        return response()->json(compact('sales'));
    }

    public function graphicalView()
    {
        return view('reports.transactions.graphical');
    }
}
