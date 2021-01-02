<?php

namespace App\Http\Controllers;

use App\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SizeController extends Controller
{
    public function index()
    {
        return view('settings.sizes.index');
    }


    public function fetchsize($id)
    {

        $size = Size::with('products')->find($id);
        $message = "";
        if ($size == null) {
            $message = 'size not found!';
        }

        return response()->json(compact('size', 'message'));
    }
    public function savesize(Request $request)
    {
        $rules = [
            'name' => 'required|unique:sizes',
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

        $size = new Size();
        $message = '';
        $size->name = $request->name;
        $size->slug = $str->slug($request->name);
        if ($size->save()) {
            $message = 'saved successfully';
        } else {
            $message = 'not saved!';
        }

        return response()->json(compact('message'));
    }

    public function updatesize(Request $request, $id)
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

        $size = Size::find($id);
        $message = '';
        if ($size != null) {
            if (strtolower($size->name) != strtolower($request->name)) {
                $rules = [
                    'name' => 'required|unique:sizes',
                ];
                $input = $request->all();

                $validator = Validator::make($input, $rules);
                if ($validator->fails()) {
                    $response = [
                        'errors' => $validator->errors()
                    ];

                    return response()->json($response, 200);
                }

                $size->name = $request->name;
                $size->slug = $str->slug($request->name);
                if ($size->save()) {
                    $message = 'updated successfully';
                } else {
                    $message = 'not updated!';
                }
            } else {
                $size->name = $request->name;
                $size->slug = $str->slug($request->name);
                if ($size->save()) {
                    $message = 'updated successfully';
                } else {
                    $message = 'not updated!';
                }
            }
        } else {
            $message = 'size not found!';
        }


        return response()->json(compact('message'));
    }
}
