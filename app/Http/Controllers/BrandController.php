<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        return view('settings.brands.index');
    }
    public function fetchBrand($id)
    {

        $brand = Brand::with('products')->find($id);
        $message = "";
        if ($brand == null) {
            $message = 'brand not found!';
        }

        return response()->json(compact('brand', 'message'));
    }
    public function saveBrand(Request $request)
    {
        $rules = [
            'name' => 'required|unique:brands',
        ];
        $input = $request->all();

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()
            ];

            return response()->json($response, 200);
        }
        $str = new Str();

        $brand = new Brand();
        $message = '';
        $brand->name = $request->name;
        $brand->slug = $str->slug($request->name);
        if ($brand->save()) {
            $message = 'saved successfully';
        } else {
            $message = 'not saved!';
        }

        return response()->json(compact('message'));
    }

    public function updateBrand(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
        ];
        $input = $request->all();

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()
            ];

            return response()->json($response, 200);
        }
        $str = new Str();

        $brand = Brand::find($id);
        $message = '';
        if ($brand != null) {
            if (strtolower($brand->name) != strtolower($request->name)) {
                $rules = [
                    'name' => 'required|unique:brands',
                ];
                $input = $request->all();

                $validator = Validator::make($input, $rules);
                if ($validator->fails()) {
                    $response = [
                        'errors' => $validator->errors()
                    ];

                    return response()->json($response, 200);
                }

                $brand->name = $request->name;
                $brand->slug = $str->slug($request->name);
                if ($brand->save()) {
                    $message = 'updated successfully';
                } else {
                    $message = 'not updated!';
                }
            } else {
                $brand->name = $request->name;
                $brand->slug = $str->slug($request->name);
                if ($brand->save()) {
                    $message = 'updated successfully';
                } else {
                    $message = 'not updated!';
                }
            }
        } else {
            $message = 'brand not found!';
        }


        return response()->json(compact('message'));
    }
}
