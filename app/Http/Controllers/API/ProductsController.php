<?php

namespace App\Http\Controllers\API;

use App\Brand;
use App\Http\Controllers\Controller;
use App\Prodgroup;
use App\Product;
use App\Size;
use App\Transaction;
use Illuminate\Http\Request;

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
        $products = Product::with('prodgroup', 'brands', 'sizes', 'sizeprices')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->orWhere('barcode', 'like', '%' . $query . '%')->orWhere('category', 'like', '%' . $query . '%')->orWhere('company_name', 'like', '%' . $query . '%')->paginate($rowsPerPage);

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
            'code' => rand(11111111111111, 999999999999),
            'products' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'subtotal' => $subtotal,
            'type_of_transaction' => $type_of_transaction,
        ]);

        $transID = $trans->id;


        return response()->json(compact('transID'));$
    }
}
