<?php

namespace App\Http\Controllers;

use App\Prodgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index()
    {
        return view('settings.groups.index');
    }
    public function fetchgroup($id)
    {

        $group = Prodgroup::with('products')->find($id);
        $message = "";
        if ($group == null) {
            $message = 'group not found!';
        }

        return response()->json(compact('group', 'message'));
    }
    public function savegroup(Request $request)
    {
        $rules = [
            'name' => 'required|unique:prodgroups',
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

        $group = new Prodgroup();
        $message = '';
        $group->name = $request->name;
        $group->slug = $str->slug($request->name);
        if ($group->save()) {
            $message = 'saved successfully';
        } else {
            $message = 'not saved!';
        }

        return response()->json(compact('message'));
    }

    public function updategroup(Request $request, $id)
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

        $group = Prodgroup::find($id);
        $message = '';
        if ($group != null) {
            if (strtolower($group->name) != strtolower($request->name)) {
                $rules = [
                    'name' => 'required|unique:prodgroups',
                ];
                $input = $request->all();

                $validator = Validator::make($input, $rules);
                if ($validator->fails()) {
                    $response = [
                        'errors' => $validator->errors()
                    ];

                    return response()->json($response, 200);
                }

                $group->name = $request->name;
                $group->slug = $str->slug($request->name);
                if ($group->save()) {
                    $message = 'updated successfully';
                } else {
                    $message = 'not updated!';
                }
            } else {
                $group->name = $request->name;
                $group->slug = $str->slug($request->name);
                if ($group->save()) {
                    $message = 'updated successfully';
                } else {
                    $message = 'not updated!';
                }
            }
        } else {
            $message = 'group not found!';
        }


        return response()->json(compact('message'));
    }
}
