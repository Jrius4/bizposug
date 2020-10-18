<?php

namespace App\Http\Controllers\API;

use App\Brand;
use App\Http\Controllers\Controller;
use App\Prodgroup;
use App\Product;
use App\Sale;
use App\Size;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function fetchProducts()
    {
        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'name';

        if ($rowsPerPage == -1) {
            $rowsPerPage = Product::count();
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
        $products = Product::with('prodgroup', 'brands', 'sizes', 'sizeprices', 'supplier')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->orWhere('barcode', 'like', '%' . $query . '%')->orWhere('category', 'like', '%' . $query . '%')->orWhere('company_name', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('products', 'sortRowsBy'));
    }

    public function fetchProdgroups()
    {

        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'name';
        if ($rowsPerPage == -1) {
            $rowsPerPage = Prodgroup::count();
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
        $groups = Prodgroup::with('products')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('groups', 'sortRowsBy'));
    }

    public function fetchBrands()
    {
        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'name';
        if ($rowsPerPage == -1) {
            $rowsPerPage = Brand::count();
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
        $brands = Brand::with('products')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('brands', 'sortRowsBy'));
    }

    public function fetchSizes()
    {
        $query = request()->query('keywords');
        $rowsPerPage = request()->query('rowsPerPage');
        $sortRowsBy = 'name';

        if ($rowsPerPage == -1) {
            $rowsPerPage = Size::count();
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
        $sizes = Size::with('products')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->paginate($rowsPerPage);

        return response()->json(compact('sizes', 'sortRowsBy'));
    }

    public function savePayment(Request $request)
    {

        $items = json_decode($request->items, true);
        $subtotal = $request->subtotal;
        $discount = $request->discount;
        $total = $request->total;
        $type_of_transaction = $request->type_of_transaction;

        $tran = new Transaction();
        $trans = $tran->create([
            'code' =>  '4' . strval(rand(11111111, 99999999)) . "5",
            'products' => $items,
            'discount' => $discount,
            'total' => $total,
            'subtotal' => $subtotal,
            'type_of_transaction' => $type_of_transaction,
        ]);

        $tran->code = '4' . strval(rand(11111111, 99999999)) . "5";
        $tran->products = $items;
        $tran->discount = $discount;
        $tran->total = $total;
        $tran->subtotal = $subtotal;
        $tran->type_of_transaction = $type_of_transaction;

        if ($tran->save()) {


            foreach ($items as $item) {
                $sales = new Sale();
                $sales->name = $item['name'];
                $sales->barcode = $item['barcode'];
                $sales->company_name = $item['company_name'];
                $sales->cost_price = $item['cost_price'];
                $sales->wholesale_price = $item['wholesale_price'];
                $sales->retailsale_price = $item['retailsale_price'];
                $sales->supplier = $item['supplier_id'] != null ? $item['supplier']['name'] : null;
                $sales->tax_percentage = $item['tax_percentage'];
                $sales->qty = $item['qty'];
                $sales->brand = $item['brands'][0]['name'];
                $sales->size = $item['sizes'][0]['name'];
                $sales->sizeprice = $item['sizeprices'][0]['name'];
                $sales->prodgroup = $item['prodgroup']['name'];
                $sales->amount = $type_of_transaction == 'Whole Sale' ? $item['qty'] * $item['wholesale_price'] : $item['qty'] * $item['retailsale_price'];
                $sales->wholeprice = $type_of_transaction == 'Whole Sale' ? 1 : 0;
                $sales->retailprice = $type_of_transaction != 'Whole Sale' ? 1 : 0;

                $sales->save();
            }
        }

        $transID = $trans->id;

        return response()->json(compact('transID'));
    }
}
