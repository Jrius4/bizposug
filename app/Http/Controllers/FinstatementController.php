<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Finstatement;

class FinstatementController extends Controller
{
    public  function index(Request $request)
    {
        $date = new Carbon();
        $queryType = $request->query('queryType') !== null ? strtolower($request->query('queryType')) : 'daily';
        $finstatements = [];
        if ($queryType == 'daily') {
            $finstatements = DB::select('
                SELECT COUNT(*) AS freq,
                DATE(pa.created_at) AS sale_day,
                SUM(CASE WHEN pa.type = "sale" then pa.amount ELSE 0 END) AS fin_sale,
                SUM(CASE WHEN pa.type = "expense" then pa.amount ELSE 0 END) AS fin_expense
                FROM finstatements AS pa GROUP BY sale_day ORDER BY sale_day DESC;
            ');
        } else if ($queryType == 'daily_by_expense') {
            $finstatements = DB::select('
            SELECT COUNT(*) AS freq,
            DATE(pa.created_at) AS sale_day,
            SUM(CASE WHEN pa.sub_type = "worker" then pa.amount ELSE 0 END) AS fin_expense_worker,
            SUM(CASE WHEN pa.sub_type = "supplier" then pa.amount ELSE 0 END) AS fin_expense_supplier
            FROM finstatements AS pa WHERE pa.type = "expense" GROUP BY sale_day ORDER BY sale_day DESC;
            ');
        } else if ($queryType == 'daily_by_sales') {
            $finstatements = DB::select('
            SELECT COUNT(*) AS freq,
            DATE(pa.created_at) AS sale_day,
            SUM(CASE WHEN pa.sub_type = "Whole Sale" then pa.amount ELSE 0 END) AS fin_whole_sale,
            SUM(CASE WHEN pa.sub_type = "Retail Sale" then pa.amount ELSE 0 END) AS fin_retail_sale
            FROM finstatements AS pa WHERE pa.type = "sale" GROUP BY sale_day ORDER BY sale_day DESC;
            ');
        } else if ($queryType == 'weekly') {
            $finstatements = DB::select('
                SELECT COUNT(*) AS freq,
                FLOOR((DAY(created_at)-1)/7 + 1) AS week_of_the_month,
                MONTHNAME(created_at) AS mnth, YEAR(created_at) AS yr,
                SUM(CASE WHEN pa.type = "sale" then pa.amount ELSE 0 END) AS fin_sale,
                SUM(CASE WHEN pa.type = "expense" then pa.amount ELSE 0 END) AS fin_expense
                FROM finstatements AS pa GROUP BY week_of_the_month,mnth,yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'weekly_by_expense') {
            $finstatements = DB::select('
                SELECT COUNT(*) AS freq,
                FLOOR((DAY(created_at)-1)/7 + 1) AS week_of_the_month,
                MONTHNAME(created_at) AS mnth, YEAR(created_at) AS yr,
                SUM(CASE WHEN pa.sub_type = "worker" then pa.amount ELSE 0 END) AS fin_expense_worker,
                SUM(CASE WHEN pa.sub_type = "supplier" then pa.amount ELSE 0 END) AS fin_expense_supplier
                FROM finstatements AS pa WHERE pa.type = "expense" GROUP BY week_of_the_month,mnth,yr ORDER BY pa.created_at DESC;
                ');
        } else if ($queryType == 'weekly_by_sales') {
            $finstatements = DB::select('
                SELECT COUNT(*) AS freq,
                FLOOR((DAY(created_at)-1)/7 + 1) AS week_of_the_month,
                MONTHNAME(created_at) AS mnth, YEAR(created_at) AS yr,
                SUM(CASE WHEN pa.sub_type = "Whole Sale" then pa.amount ELSE 0 END) AS fin_whole_sale,
                SUM(CASE WHEN pa.sub_type = "Retail Sale" then pa.amount ELSE 0 END) AS fin_retail_sale
                FROM finstatements AS pa WHERE pa.type = "sale" GROUP BY week_of_the_month,mnth,yr ORDER BY pa.created_at DESC;
                ');
        } else if ($queryType == 'monthly') {
            $finstatements = DB::select('
            SELECT COUNT(*) AS freq,
                MONTHNAME(created_at) AS mnth, YEAR(created_at) AS yr,
                SUM(CASE WHEN pa.type = "sale" then pa.amount ELSE 0 END) AS fin_sale,
                SUM(CASE WHEN pa.type = "expense" then pa.amount ELSE 0 END) AS fin_expense
                FROM finstatements AS pa GROUP BY mnth,yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'monthly_by_expense') {
            $finstatements = DB::select('
                SELECT COUNT(*) AS freq,
                MONTHNAME(created_at) AS mnth, YEAR(created_at) AS yr,
                SUM(CASE WHEN pa.sub_type = "worker" then pa.amount ELSE 0 END) AS fin_expense_worker,
            SUM(CASE WHEN pa.sub_type = "supplier" then pa.amount ELSE 0 END) AS fin_expense_supplier
                FROM finstatements AS pa WHERE pa.type = "expense"  GROUP BY mnth,yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'monthly_by_sales') {
            $finstatements = DB::select('
                SELECT COUNT(*) AS freq,
                MONTHNAME(created_at) AS mnth, YEAR(created_at) AS yr,
                SUM(CASE WHEN pa.sub_type = "Whole Sale" then pa.amount ELSE 0 END) AS fin_whole_sale,
            SUM(CASE WHEN pa.sub_type = "Retail Sale" then pa.amount ELSE 0 END) AS fin_retail_sale
                FROM finstatements AS pa WHERE pa.type = "sale"  GROUP BY mnth,yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'yearly') {
            $finstatements = DB::select('
            SELECT
            YEAR(created_at) AS yr,
            SUM(CASE WHEN pa.type = "sale" then pa.amount ELSE 0 END) AS fin_sale,
            SUM(CASE WHEN pa.type = "expense" then pa.amount ELSE 0 END) AS fin_expense
            FROM finstatements AS pa GROUP BY yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'yearly_by_expense') {
            $finstatements = DB::select('
            SELECT
            YEAR(created_at) AS yr,
            SUM(CASE WHEN pa.sub_type = "worker" then pa.amount ELSE 0 END) AS fin_expense_worker,
            SUM(CASE WHEN pa.sub_type = "supplier" then pa.amount ELSE 0 END) AS fin_expense_supplier
            FROM finstatements AS pa WHERE pa.type = "expense"  GROUP BY yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'yearly_by_sales') {
            $finstatements = DB::select('
            SELECT
            YEAR(created_at) AS yr,
            SUM(CASE WHEN pa.sub_type = "Whole Sale" then pa.amount ELSE 0 END) AS fin_whole_sale,
            SUM(CASE WHEN pa.sub_type = "Retail Sale" then pa.amount ELSE 0 END) AS fin_retail_sale
            FROM finstatements AS pa WHERE pa.type = "sale" GROUP BY yr ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'groups') {
            $finstatements = DB::select('
                SELECT COUNT(*) AS freq,
                SUM(CASE WHEN pa.type = "sale" then pa.amount ELSE 0 END) AS fin_sale,
                SUM(CASE WHEN pa.type = "expense" then pa.amount ELSE 0 END) AS fin_expense
                FROM finstatements AS pa GROUP BY pa.type ORDER BY pa.created_at DESC;
            ');
        } else if ($queryType == 'all') {
            $finstatements = DB::select('
            SELECT COUNT(*) AS freq, SUM(amount) AS total
            FROM finstatements;
            ');
        } else if ($queryType == 'interval') {
            $start = $request->query('start');
            $end = $request->query('end');

            $startDate = $date->parse($start)->format('Y-m-d');
            $endDate = $date->parse($end)->format('Y-m-d');


            $finstatements =  DB::select("SELECT COUNT(*) AS freq,
                '" . $startDate . "_" . $endDate . "' AS p_period,
                SUM(pa.amount) AS total,
                SUM(CASE WHEN pa.type = 'sale' then pa.amount ELSE 0 END) AS fin_sale,
                SUM(CASE WHEN pa.type = 'expense' then pa.amount ELSE 0 END) AS fin_expense
                FROM finstatements AS pa WHERE pa.created_at
                BETWEEN DATE('" . $startDate . "') AND DATE('" . $endDate . "')
                GROUP BY p_period ORDER BY pa.created_at;");
        }

        $amount = count($finstatements);
        $page = $request->query('page') != null ? $request->query('page') : 1;
        $rowsPerPage = $request->query('rowsPerPage') != null ? $request->query('rowsPerPage') : 10;
        if ($rowsPerPage == "-1") $rowsPerPage = $amount;
        $offset = ($page * $rowsPerPage) - $rowsPerPage;
        $finstatements = new LengthAwarePaginator(
            array_slice($finstatements, $offset, $rowsPerPage, false),
            $amount,
            $rowsPerPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );




        return response()->json(compact('finstatements', 'queryType'));
    }
}
