<?php

namespace App\Http\Controllers;

use App\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function fetchSales()
    {

        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'created_at';


        if ($rowsPerPage == -1) {
            $rowsPerPage = Sale::count();
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
        $sales = Sale::orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('sales', 'sortRowsBy'));
    }

    public function summarySalesCategories(Request $request)
    {
        $date = new Carbon();
        $queryType = $request->query('queryType') !== null ? strtolower($request->query('queryType')) : 'daily';
        $sales = [];
        $sortRowsBy = $request->query('sortRowsBy') !== null ?  strtolower($request->query('sortRowsBy')) : 'sales.created_at';
        $sortDesc = request()->query('sortDesc') == 'false' ? false : true;


        if ($queryType == 'daily') {
            $sales = DB::select('
                SELECT COUNT(*) AS freq,
                SUM(sales.amount) AS amount , SUM(sales.qty) AS no_products,
                DATE(sales.created_at) AS sale_day,
                sales.prodgroup
                FROM sales
                GROUP BY sale_day,sales.prodgroup ORDER BY ' . $sortRowsBy . ' ' . ($sortDesc ? 'desc' : 'asc') . ';
            ');
        } else if ($queryType == 'weekly') {
            $sales = DB::select('
            SELECT COUNT(*) AS freq, SUM(sales.qty) AS no_products,SUM(sales.amount) AS amount,
            FLOOR((DAY(sales.created_at)-1)/7 + 1) AS week_of_the_month,
            MONTHNAME(sales.created_at) AS "month_of_the_year", YEAR(sales.created_at) AS year,
            sales.prodgroup
            FROM sales
            GROUP BY year,month_of_the_year,week_of_the_month,sales.prodgroup ORDER BY ' . $sortRowsBy . ' ' . ($sortDesc ? 'desc' : 'asc') . ';
        ');
        } else if ($queryType == 'monthly') {
            $sales = DB::select('
            SELECT COUNT(*) AS freq, SUM(sales.qty) AS no_products,SUM(sales.amount) AS amount,
            MONTHNAME(sales.created_at) AS "mnth", YEAR(sales.created_at) AS yr,
            sales.prodgroup
            FROM sales
            GROUP BY yr,mnth,sales.prodgroup ORDER BY ' . $sortRowsBy . ' ' . ($sortDesc ? 'desc' : 'asc') . ';
        ');
        } else if ($queryType == 'yearly') {
            $sales = DB::select('
            SELECT COUNT(*) AS freq, SUM(sales.qty) AS no_products,SUM(sales.amount) AS amount,
            YEAR(sales.created_at) AS yr,
            sales.prodgroup
            FROM sales
            GROUP BY yr,sales.prodgroup ORDER BY ' . $sortRowsBy . ' ' . ($sortDesc ? 'desc' : 'asc') . ';
        ');
        } else if ($queryType == 'interval') {
            $start = $request->query('start');
            $end = $request->query('end');

            $startDate = $date->parse($start)->format('Y-m-d');
            $endDate = $date->parse($end)->format('Y-m-d');
            $sales = DB::select("
            SELECT COUNT(*) AS freq, SUM(sales.qty) AS no_products,
            '" . $startDate . "_" . $endDate . "' AS p_period,SUM(sales.qty) AS no_products,SUM(sales.amount) AS amount,
            sales.prodgroup
            FROM sales  WHERE sales.created_at
            BETWEEN DATE('" . $startDate . "') AND DATE('" . $endDate . "') GROUP BY p_period,sales.prodgroup ORDER BY " . $sortRowsBy . " "  . ($sortDesc ? 'desc' : 'asc') . ";
            ");
        }


        $total = count($sales);
        $page = $request->query('page') != null ? $request->query('page') : 1;
        $rowsPerPage = $request->query('rowsPerPage') != null ? $request->query('rowsPerPage') : 10;
        if ($rowsPerPage == "-1") $rowsPerPage = $total;
        $offset = ($page * $rowsPerPage) - $rowsPerPage;
        $sales = new LengthAwarePaginator(
            array_slice($sales, $offset, $rowsPerPage, false),
            $total,
            $rowsPerPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );




        return response()->json(compact('sales', 'queryType', 'sortRowsBy', 'sortDesc'));
    }


    public function summarySales(Request $request)
    {
        $date = new Carbon();
        $queryType = $request->query('queryType') !== null ? strtolower($request->query('queryType')) : 'daily';
        $sales = [];
        $sortRowsBy = $request->query('sortRowsBy') !== null ?  strtolower($request->query('sortRowsBy')) : 'sales.created_at';
        $sortDesc = request()->query('sortDesc') == 'false' ? false : true;


        if ($queryType == 'daily') {
            $sales = DB::select('
                SELECT COUNT(*) AS freq,
                SUM(sales.amount) AS amount , SUM(sales.qty) AS no_products,
                DATE(sales.created_at) AS sale_day,
                sales.name
                FROM sales
                GROUP BY sale_day,sales.name ORDER BY ' . $sortRowsBy . ' ' . ($sortDesc ? 'desc' : 'asc') . ';
            ');
        } else if ($queryType == 'weekly') {
            $sales = DB::select('
            SELECT COUNT(*) AS freq, SUM(sales.qty) AS no_products,SUM(sales.amount) AS amount,
            FLOOR((DAY(sales.created_at)-1)/7 + 1) AS week_of_the_month,
            MONTHNAME(sales.created_at) AS "month_of_the_year", YEAR(sales.created_at) AS year,
            sales.name
            FROM sales
            GROUP BY year,month_of_the_year,week_of_the_month,sales.name ORDER BY ' . $sortRowsBy . ' ' . ($sortDesc ? 'desc' : 'asc') . ';
        ');
        } else if ($queryType == 'monthly') {
            $sales = DB::select('
            SELECT COUNT(*) AS freq, SUM(sales.qty) AS no_products,SUM(sales.amount) AS amount,
            MONTHNAME(sales.created_at) AS "mnth", YEAR(sales.created_at) AS yr,
            sales.name
            FROM sales
            GROUP BY yr,mnth,sales.name ORDER BY ' . $sortRowsBy . ' ' . ($sortDesc ? 'desc' : 'asc') . ';
        ');
        } else if ($queryType == 'yearly') {
            $sales = DB::select('
            SELECT COUNT(*) AS freq, SUM(sales.qty) AS no_products,SUM(sales.amount) AS amount,
            YEAR(sales.created_at) AS yr,
            sales.name
            FROM sales
            GROUP BY yr,sales.name ORDER BY ' . $sortRowsBy . ' ' . ($sortDesc ? 'desc' : 'asc') . ';
        ');
        } else if ($queryType == 'interval') {
            $start = $request->query('start');
            $end = $request->query('end');

            $startDate = $date->parse($start)->format('Y-m-d');
            $endDate = $date->parse($end)->format('Y-m-d');
            $sales = DB::select("
            SELECT COUNT(*) AS freq, SUM(sales.qty) AS no_products,
            '" . $startDate . "_" . $endDate . "' AS p_period,SUM(sales.qty) AS no_products,SUM(sales.amount) AS amount,
            sales.name
            FROM sales  WHERE sales.created_at
            BETWEEN DATE('" . $startDate . "') AND DATE('" . $endDate . "') GROUP BY p_period,sales.name ORDER BY " . $sortRowsBy . " "  . ($sortDesc ? 'desc' : 'asc') . ";
            ");
        }


        $total = count($sales);
        $page = $request->query('page') != null ? $request->query('page') : 1;
        $rowsPerPage = $request->query('rowsPerPage') != null ? $request->query('rowsPerPage') : 10;
        if ($rowsPerPage == "-1") $rowsPerPage = $total;
        $offset = ($page * $rowsPerPage) - $rowsPerPage;
        $sales = new LengthAwarePaginator(
            array_slice($sales, $offset, $rowsPerPage, false),
            $total,
            $rowsPerPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );




        return response()->json(compact('sales', 'queryType', 'sortRowsBy', 'sortDesc'));
    }



    public function salesGraphicalView()
    {

        return view('reports.sales.graphical');
    }

    public function salesCategoriesGraphicalView()
    {

        return view('reports.sales.graphical-categories');
    }
}
