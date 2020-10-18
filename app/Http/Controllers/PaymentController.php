<?php

namespace App\Http\Controllers;

use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


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

    public function summaryPayments(Request $request)
    {
        $date = new Carbon();
        $queryType = $request->query('queryType') !== null ? strtolower($request->query('queryType')) : 'daily';
        $payments = [];
        if ($queryType == 'daily') {
            $payments = DB::select('
            SELECT COUNT(*) AS freq, SUM(paid) AS paidTotal ,
            DATE(created_at) AS transact_day
            FROM payments
            GROUP BY transact_day ORDER BY transact_day desc;
            ');
        } else if ($queryType == 'daily_by_group') {
            $payments = DB::select('
            SELECT COUNT(*) AS freq,
            DATE(pa.created_at) AS transact_day, SUM(CASE WHEN pa.type_payment = "worker" then pa.paid ELSE 0 END) AS worker,
            SUM(CASE WHEN pa.type_payment = "supplier" then pa.paid ELSE 0 END) AS supplier
            FROM payments AS pa GROUP BY transact_day ORDER BY transact_day DESC;
            ');
        } else if ($queryType == 'weekly') {
            $payments = DB::select('
                SELECT COUNT(*) AS freq, SUM(paid) AS paid,
                FLOOR((DAY(created_at)-1)/7 + 1) AS week_of_the_month,
                MONTHNAME(created_at) AS "month_of_the_year", YEAR(created_at) AS year
                FROM payments
                GROUP BY year,month_of_the_year,week_of_the_month ORDER BY created_at desc;
            ');
        } else if ($queryType == 'weekly_by_group') {
            $payments = DB::select('
                SELECT COUNT(*) AS freq,
                FLOOR((DAY(created_at)-1)/7 + 1) AS week_of_the_month,
                MONTHNAME(created_at) AS mnth, YEAR(created_at) AS yr,
                SUM(CASE WHEN pa.type_payment = "worker" then pa.paid ELSE 0 END) AS worker,
                SUM(CASE WHEN pa.type_payment = "supplier" then pa.paid ELSE 0 END) AS supplier
                FROM payments AS pa GROUP BY week_of_the_month,mnth,yr ORDER BY pa.created_at DESC;
                ');
        } else if ($queryType == 'monthly') {
            $payments = DB::select('
            SELECT COUNT(*) AS freq, SUM(paid) AS paid,
            MONTHNAME(created_at) AS "mnth", YEAR(created_at) AS yr
            FROM payments as pa
            GROUP BY yr,mnth ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'monthly_by_group') {
            $payments = DB::select('
                SELECT COUNT(*) AS freq,
                MONTHNAME(created_at) AS mnth, YEAR(created_at) AS yr,
                SUM(CASE WHEN pa.type_payment = "worker" then pa.paid ELSE 0 END) AS worker,
                SUM(CASE WHEN pa.type_payment = "supplier" then pa.paid ELSE 0 END) AS supplier
                FROM payments AS pa GROUP BY mnth,yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'yearly') {
            $payments = DB::select('
            SELECT COUNT(*) AS freq, SUM(paid) AS paid,
            YEAR(created_at) AS yr
            FROM payments as pa
            GROUP BY yr ORDER BY pa.created_at;
            ');
        } else if ($queryType == 'yearly_by_group') {
            $payments = DB::select('
            SELECT
            YEAR(created_at) AS yr,
            SUM(CASE WHEN pa.type_payment = "worker" then pa.paid ELSE 0 END) AS worker,
            SUM(CASE WHEN pa.type_payment = "supplier" then pa.paid ELSE 0 END) AS supplier
            FROM payments AS pa GROUP BY yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'groups') {
            $payments = DB::select('
                SELECT COUNT(*) AS freq,
                SUM(CASE WHEN pa.type_payment = "worker" then pa.paid ELSE 0 END) AS worker,
                SUM(CASE WHEN pa.type_payment = "supplier" then pa.paid ELSE 0 END) AS supplier
                FROM payments AS pa GROUP BY pa.type_payment ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'all') {
            $payments = DB::select('
            SELECT COUNT(*) AS freq, SUM(paid) AS paid
            FROM payments;
            ');
        } else if ($queryType == 'interval') {
            $start = $request->query('start');
            $end = $request->query('end');

            $startDate = $date->parse($start)->format('Y-m-d');
            $endDate = $date->parse($end)->format('Y-m-d');
            $payments = DB::select("
            SELECT COUNT(*) AS freq,
            '" . $startDate . "_" . $endDate . "' AS p_period,SUM(ex.paid) AS paid
            FROM payments AS ex WHERE ex.created_at
            BETWEEN DATE('" . $startDate . "') AND DATE('" . $endDate . "') GROUP BY p_period;
            ");
        } else if ($queryType == 'interval_by_group') {
            $start = $request->query('start');
            $end = $request->query('end');

            $startDate = $date->parse($start)->format('Y-m-d');
            $endDate = $date->parse($end)->format('Y-m-d');


            $payments =  DB::select("SELECT COUNT(*) AS freq,
                '" . $startDate . "_" . $endDate . "' AS p_period,
                SUM(pa.paid) AS paid,
                SUM(CASE WHEN pa.type_payment = 'worker' then pa.paid ELSE 0 END) AS worker,
                SUM(CASE WHEN pa.type_payment = 'supplier' then pa.paid ELSE 0 END) AS supplier
                FROM payments AS pa WHERE pa.created_at
                BETWEEN DATE('" . $startDate . "') AND DATE('" . $endDate . "')
                GROUP BY p_period ORDER BY pa.created_at;");
        }

        $total = count($payments);
        $page = $request->query('page') != null ? $request->query('page') : 1;
        $rowsPerPage = $request->query('rowsPerPage') != null ? $request->query('rowsPerPage') : 10;
        if ($rowsPerPage == "-1") $rowsPerPage = $total;
        $offset = ($page * $rowsPerPage) - $rowsPerPage;
        $payments = new LengthAwarePaginator(
            array_slice($payments, $offset, $rowsPerPage, false),
            $total,
            $rowsPerPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
        return response()->json(compact('payments', 'queryType'));
    }

    public function graphicalView()
    {
        return view('reports.payments.graphical');
    }
}
