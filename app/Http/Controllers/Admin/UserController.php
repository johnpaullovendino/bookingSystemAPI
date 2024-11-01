<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        User::orderBy("created_at","desc")->paginate(10);
        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'code' => 200
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            // 'role' => 'required|string',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'message'=> $validator->errors()->first(),
                'code '=> 400,
                'data' => []
            ]);
        }

        $users = User::create([
            'name'=> $request->name,
            // 'role'=> $request->role,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
        ]);

        return response()->json([
            'success'=> true,
            'message'=> 'User Created Successfully',
            'code' => 201,
            'data'=> $users
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            // 'role' => 'required|string',
            'email' => 'required|email|max:100|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'message'=> $validator->errors()->first(),
                'code '=> 400,
                'data' => []
            ]);
        }
        User::findOrFail($id)->update([
            'name'=> $request->name,
            // 'role'=> $request->role,
            'email'=> $request->email,
        ]);

        return response()->json([
            'success'=> true,
            'message'=> 'User Updated Successfully',
            'code' => 202
        ]);
    } 

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response()->json([
            'success'=> true,
            'message'=> 'Business deleted successfully',
            'code'=> 201,
        ]);
    }
}
