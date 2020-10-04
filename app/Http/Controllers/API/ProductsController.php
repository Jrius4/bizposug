<?php

namespace App\Http\Controllers\API;

use App\Brand;
use App\Http\Controllers\Controller;
use App\Prodgroup;
use App\Product;
use App\Size;
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
        $products = Product::with('prodgroup', 'brands', 'sizes')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->orWhere('barcode', 'like', '%' . $query . '%')->orWhere('category', 'like', '%' . $query . '%')->orWhere('company_name', 'like', '%' . $query . '%')->paginate($rowsPerPage);

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
}
