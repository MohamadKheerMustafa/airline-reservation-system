<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserInfoResource;
use App\Models\Passenger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:8|max:20'
        ]);
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'msg' => 'incorrect username or password'
            ], 401);
        }
        $token = $user->createToken('apiToken')->plainTextToken;
        $res = [
            'user' => UserInfoResource::make($user),
            'token' => $token
        ];

        return response($res, 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'user logged out',
        ];
    }

    public function sign_up(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'address' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            "phone" => $data['phone'],
            "address" => $data['address'],
        ]);
        // $passenger = Passenger::create([
        //     'first_name' => strtok($data['name']," "),
        //     'last_name' => ,
        //     'date_of_birth' => ,
        //     'gender' => ,
        //     'nationality' => ,
        // ]);

        $res = [
            'user' => $user,
        ];
        return response($res, 201);
    }

    public function addEmployee(Request $request)
    {
        if (auth()->user()->is_admin == 1) {
            $user = User::findOrFail($request->user_id);
            $user->update([
                'is_Employee' => $request->status
            ]);
            return $user;
        } else {
            return response()->json([
                'message' => "not Authorized"
            ], 401);
        }
    }
}
