<?php

namespace App\Http\Controllers\API;

use App\Brand;
use App\Finstatement;
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
use Illuminate\Support\Facades\Validator;

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


        $sortDesc = request()->query('sortDesc') !== null ? true : false;

        // if (request()->query('sortDesc') !== null) {
        //     $sortDesc = request()->query('sortDesc') == true ? true : false;
        // } else {
        //     $sortDesc = false;
        // }
        if (request()->query('sortRowsBy') !== null) {
            $sortRowsBy = request()->query('sortRowsBy');
        } else {
            $sortRowsBy = 'name';
        }
        if ($sortRowsBy == 'name') {
            $sortRowsBy = 'name';
        }
        $products = Product::with('prodgroup', 'brands', 'sizes', 'sizeprices', 'supplier')->orderBy($sortRowsBy, ($sortDesc ? 'desc' : 'asc'))->where('name', 'like', '%' . $query . '%')->orWhere('barcode', 'like', '%' . $query . '%')->orWhere('category', 'like', '%' . $query . '%')->orWhere('company_name', 'like', '%' . $query . '%')->paginate($rowsPerPage);
        $query = request()->query();
        $url = request()->url();

        return response()->json(compact('products', 'sortRowsBy', 'query', 'sortDesc', 'url'));
    }

    public function saveProduct(Request $request)
    {
        $rules = [
            'name' => 'required|unique:products',
            'category' => 'required',
            'cost_price' => 'required',
            'retailsale_price' => 'required',
            'wholesale_price' => 'required',
        ];

        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()
            ];

            return response()->json($response, 200);
            // return response()->json($response, 403);
        }
        $product = new Product();
        $product->name = $request->name;
        $product->avatar = '/assets/icons/cart.png';
        if ($request->barcode == null) {
            $product->barcode = '4' . strval(rand(11111111, 99999999)) . "5";
        } elseif ($request->barcode != null) {
            $product->barcode = $request->barcode;
        }

        $product->category = $request->category;
        $product->prodgroup_id = $request->prodgroup_id;
        $product->cost_price = $request->cost_price;
        $product->retailsale_price = $request->retailsale_price;
        $product->wholesale_price = $request->wholesale_price;
        $product->supplier_id = $request->supplier_id  != NULL ? $request->supplier_id : null;
        $product->brand = $request->brand  != NULL ? $request->brand : null;
        $product->quantity = $request->quantity != NULL ? $request->quantity : 0;
        $product->stock_type = $request->stockType != NULL ? $request->stockType : 'non-stock';
        $product->company_name = $request->company_name ? $request->company_name : 'UNKNOWN';
        $product->tax_percentage = $request->tax_percentage ? $request->tax_percentage : 0;
        $product->description = $request->description ? $request->description : null;
        if ($product->save()) {
            $newProduct = Product::with('brands', 'sizes')->where('name', $request->name)->first();
            if ($request->brand_id != null) {
                $brands = new Brand();
                $newProduct->brands()->attach($brands->find($request->brand_id));
            }

            if ($request->size_id != null) {
                $sizes = new Size();
                $newProduct->sizes()->attach($sizes->find($request->size_id));
            }
            $message = 'saved successfully';
        } else {
            $message = 'not saved!';
        }
        return response()->json(compact('message'));
    }
    public function fetchProduct($id)
    {
        $product = Product::with('brands', 'sizes', 'prodgroup', 'supplier')->find($id);
        $message = '';
        if ($product == null) {
            $message = 'product not found!';
        }
        return response()->json(compact('product', 'message'));
    }

    public function updateProduct(Request $request, $id)
    {

        $rules = [
            'name' => 'required',
            'category' => 'required',
            'cost_price' => 'required',
            'retailsale_price' => 'required',
            'wholesale_price' => 'required',
        ];

        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()
            ];

            return response()->json($response, 200);
            // return response()->json($response, 403);
        }
        $product = Product::find($id);
        $product->name = $request->name;
        $product->avatar = '/assets/icons/cart.png';
        if ($request->barcode == null) {
            $product->barcode = '4' . strval(rand(11111111, 99999999)) . "5";
        } elseif ($request->barcode != null) {
            $product->barcode = $request->barcode;
        }

        $product->category = $request->category;
        $product->prodgroup_id = $request->prodgroup_id;
        $product->cost_price = $request->cost_price;
        $product->retailsale_price = $request->retailsale_price;
        $product->wholesale_price = $request->wholesale_price;
        $product->supplier_id = $request->supplier_id  != NULL ? $request->supplier_id : $product->supplier_id;
        $product->brand = $request->brand  != NULL ? $request->brand : $product->brand;
        $product->quantity = $request->quantity != NULL ? $request->quantity : $product->quantity;
        $product->stock_type = $request->stockType != NULL ? $request->stockType : $product->stock_type;
        $product->company_name = $request->company_name ? $request->company_name : $product->company_name;
        $product->tax_percentage = $request->tax_percentage ? $request->tax_percentage : $product->tax_percentage;
        $product->description = $request->description ? $request->description : $product->description;
        if ($product->save()) {
            $newProduct = Product::with('brands', 'sizes')->where('name', $request->name)->first();

            $brands = new Brand();
            $newProduct->brands()->detach($brands->find($newProduct->brands()->pluck('id')));
            $newProduct->brands()->attach($brands->find($request->brand_id));



            $sizes = new Size();
            $newProduct->sizes()->detach($sizes->find($newProduct->sizes()->pluck('id')));
            $newProduct->sizes()->attach($sizes->find($request->size_id));

            $message = 'saved successfully';
        } else {
            $message = 'not saved!';
        }
        return response()->json(compact('message'));
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
        $customer_id = $request->customer_id != null ? $request->customer_id : null;

        $tran = new Transaction();

        $profit = ($request->profit !== null ? ($request->profit  - $discount) : 0);
        $loss = ($request->loss !== null ? $request->loss : 0);
        $tran->code = '4' . strval(rand(11111111, 99999999)) . "5";
        $tran->products = $items;
        $tran->discount = $discount;
        $tran->total = $total;
        $tran->subtotal = $subtotal;
        $tran->loss = $loss;
        $tran->profit = $profit;
        $tran->customer_id = $customer_id;
        $tran->type_of_transaction = $type_of_transaction;

        if ($tran->save()) {
            $products = new Product();
            foreach ($items as $item) {
                $prodt = $products->find($item['id']);
                if ($prodt != null) {
                    $prodt->update([
                        'quantity' => $prodt->quantity - $item['qty']
                    ]);
                }
            }
            $fin = new Finstatement();
            $fin->create([
                'amount' => $total,
                'items' => json_encode($items, true),
                'sub_type' => $type_of_transaction,
                'type' => 'sale',
                'profits' => $profit,
                'losses' => $loss
            ]);

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
                $sales->prodgroup = $item['prodgroup']['name'];
                $sales->amount = $type_of_transaction == 'Whole Sale' ? $item['qty'] * $item['wholesale_price'] : $item['qty'] * $item['retailsale_price'];
                $sales->wholeprice = $type_of_transaction == 'Whole Sale' ? 1 : 0;
                $sales->retailprice = $type_of_transaction != 'Whole Sale' ? 1 : 0;

                $sales->save();
            }
        }

        $transID = $tran->id;

        return response()->json(compact('transID'));
    }
}
